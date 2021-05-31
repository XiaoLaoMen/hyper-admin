<?php


namespace App\Model\Reuse;


trait TraitModel
{
    public function getOne($where)
    {
        return $this->where($where)->first();
    }

    public function getPaginatorAndCount($page,$limit,$where=[])
    {

        $skip = ($page-1)*$limit;
        $count = $this->where($where)->get()->count();
        $list = $this->where($where)
            ->skip($skip)
            ->take($limit)
            ->orderBy('id', 'desc')
            ->get();

        return ['count'=>$count,'list'=>$list];
    }

    public function getAll($where=[])
    {
        return $this->where($where)->get();
    }
}
