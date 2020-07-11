<?php


namespace App\Service;


class UserService implements UserServiceInterface
{
    /**
     * @var bool
     */
    private $enableCache;
    public function __construct(bool $enableCache)
    {
        // 接收值并储存于类属性中
        $this->enableCache = $enableCache;
    }

    public function getInfoById(int $id)
    {
        // TODO: Implement getInfoId() method.
        $result = $id + 123;
        return $result;
    }
}