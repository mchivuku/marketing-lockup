<?php namespace AdamWathan\BootForms;


class BootForm
{
	private $builder;
	private $basicFormBuilder;
	private $horizontalFormBuilder;

	public function __construct(BasicFormBuilder $basicFormBuilder, HorizontalFormBuilder $horizontalFormBuilder)
	{
		$this->basicFormBuilder = $basicFormBuilder;
		$this->horizontalFormBuilder = $horizontalFormBuilder;
	}

	public function open()
	{
		$this->builder = $this->basicFormBuilder;
		return $this->builder->open();
	}

	public function openHorizontal($columnSizes)
	{
		$this->horizontalFormBuilder->setColumnSizes($columnSizes);
		$this->builder = $this->horizontalFormBuilder;
		return $this->builder->open();
	}

	public function __call($method, $parameters)
	{
        echo 'i am here';exit;
  		return call_user_func_array(array($this->builder, $method), $parameters);
  	}
}
