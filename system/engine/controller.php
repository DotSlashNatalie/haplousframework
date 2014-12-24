<?php
class HF_Controller
{
    protected $config;
    protected $tpl;
    /** @var HF_Core  */
    protected $core;

    public function __construct($config, $core, $tpl = null)
    {
        $this->config = $config;
        $this->tpl = $tpl;
        $this->core = $core;
        $this->initdb();
    }

    protected function initdb()
    {
        if (isvarset($this->config["MYSQL_DBNAME"]) && isvarset($this->config["MYSQL_USER"]))
        {
            $pdo = new PDO(
                "mysql:dbname={$this->config['MYSQL_DBNAME']};host={$this->config['MYSQL_HOST']}",
                $this->config['MYSQL_USER'],
                $this->config['MYSQL_PASS'],
                array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                )
            );

            DB::$c = $pdo;
        }
    }

    public function loadRender($template, $parameters=array())
    {
        $this->tpl->loadTemplate($template);
        return $this->tpl->render($parameters);
    }

}