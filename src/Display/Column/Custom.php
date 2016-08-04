<?php

namespace SleepingOwl\Admin\Display\Column;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Assets\Contracts\MetaInterface;
use SleepingOwl\Admin\Contracts\AdminInterface;
use SleepingOwl\Admin\Contracts\Display\TableHeaderColumnInterface;
use SleepingOwl\Admin\Display\TableColumn;

class Custom extends TableColumn
{
    /**
     * Callback to render column contents.
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    protected $view = 'column.custom';

    /**
     * Custom constructor.
     *
     * @param null|string $label
     * @param \Closure $callback
     * @param TableHeaderColumnInterface $tableHeaderColumn
     * @param AdminInterface $admin
     * @param MetaInterface $meta
     */
    public function __construct($label = null,
                                \Closure $callback = null,
                                TableHeaderColumnInterface $tableHeaderColumn,
                                AdminInterface $admin,
                                MetaInterface $meta)
    {
        parent::__construct($label, $tableHeaderColumn, $admin, $meta);
        if (! is_null($callback)) {
            $this->setCallback($callback);
        }
    }

    /**
     * @return \Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param \Closure $callback
     *
     * @return $this
     */
    public function setCallback(\Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Get value from callback.
     *
     * @param Model $model
     *
     * @return mixed
     * @throws \Exception
     */
    protected function getValue(Model $model)
    {
        if (! is_callable($callback = $this->getCallback())) {
            throw new \Exception('Invalid custom column callback');
        }

        return call_user_func($callback, $model);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray()
    {
        return parent::toArray() + [
            'value'  => $this->getValue($this->getModel()),
            'append' => $this->getAppends(),
        ];
    }
}
