<?php
namespace app\index\controller;

use think\Controller;


class Page extends Controller
{
	
	public function index()
	{
		$table=db('student');
        $list=$table->paginate(config('paginate.list_rows'));
        $this->assign('list',$list);
        $this->assign('page',$list->render());
       return $this->fetch();
	}
}