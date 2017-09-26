<?php
abstract class FilterField
{
	abstract protected function getValues();
	abstract protected function getFilter();
	abstract protected function setValue($value);
	public function setSettings($settings)
	{
		$this->settings = $settings;
	}
	public function setFilter($filter)
	{
		$this->filter = $filter;
	}
}
