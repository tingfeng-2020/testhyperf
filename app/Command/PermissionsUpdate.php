<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\DbConnection\Db;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @Command
 */
class PermissionsUpdate extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    protected $db;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = Db::connection('mysql');

        parent::__construct('permission:update');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('权限自动更新脚本...[可选 user_id --刷新或添加该用户所有权限]');
        $this->addArgument('user_id', InputArgument::OPTIONAL, '用户id', 'Hyperf');
    }

    public function handle()
    {
        $this->line('Start.....', 'info');
        //获取配置文件
        $menu = config('permission_menu');
        if(empty($menu)) {
            $this->line('fail..配置文件不存在','error');
            return false;
        }
        $this->updateOrInsertMenu($menu);

        $this->call('permission:cache-reset');
        $this->line('Sucess.....', 'info');
        $this->line($this->input->getArgument('user_id'));
    }

    protected function updateOrInsertMenu($menu)
    {
        if(empty($menu)){
            return false;
        }
        Db::transaction(function () use($menu) {
            foreach ($menu as $k => $v) {
                $ret = $this->insertItem($this->formatItem($v));
                //添加子菜单
                if (isset($v['child']) && !empty($v['child']) && is_array($v['child'])) {
                    $this->updateOrInsertMenu($v['child']);
                }
            }
        });

        return true ;
    }

    /**
     * 格式化菜单记录
     * @param $item
     * @return array
     */
    private function formatItem($item)
    {
        $time = date('Y-m-d H:i:s');
        $tmp = [
            'name'=> isset($item['name']) ? $item['name'] : '',
            'guard_name'=>isset($item['guard_name']) ? $item['guard_name'] : 'web',
            'display_name'=>isset($item['display_name']) ? $item['display_name'] : '',
            'url'=>isset($item['url']) ? $item['url'] : '',
            'parent_id'=>isset($item['parent_id']) ? $item['parent_id'] : 0 ,
            'sort'=>isset($item['sort']) ? $item['sort'] : 0,
            'created_at'=>isset($item['created_at']) ? $item['created_at'] : $time,
            'updated_at'=>isset($item['updated_at']) ? $item['updated_at'] : $time,
        ];
        return $tmp ;
    }

    /**
     * 更新数据
     * @param $item
     * @return mixed
     */
    private function insertItem($item){
        $parent_id = 0 ;
        if (!empty($item['parent_id']) && is_string($item['parent_id'])) {
            $parent_id = $this->db->table('permissions')->where(['name' => $item['parent_id']])->value('id');
            if(empty($parent_id)){
                return false ;
            }
        }
        $item['parent_id'] = $parent_id;
        return $this->db->table('permissions')->updateOrInsert(['name' => $item['name']],$item);
    }
}
