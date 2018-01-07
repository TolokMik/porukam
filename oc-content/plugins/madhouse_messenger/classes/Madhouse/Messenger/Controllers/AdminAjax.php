<?php

class Madhouse_Messenger_Controllers_AdminAjax extends AdminSecBaseModel
{
    public function __construct() {
        parent::__construct();
        $this->ajax = true;
    }

    public function doModel()
    {
        parent::doModel();
        switch (Params::getParam("do")) {
            case "admin_upgrade_post":
                $upgradingVersions = Madhouse_Messenger_Plugin::getUpgradingVersions();

                if (empty($upgradingVersions)) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode(
                        array(
                            "status" => "error",
                            "data" => array(
                                "done" => true
                            )
                        )
                    );
                }

                if (in_array("1.40.0", $upgradingVersions)) {
                    $this->doUpgrade140();
                }
            break;
            default:
                echo json_encode(array('error' => __('No action defined', mdh_current_plugin_name())));
            break;
        }
    }

    public function doUpgrade140() {
        $version = Params::getParam("version");

        $page = Params::getParam("p");
        $toProcess = Params::getParam("n");

        if ($page == 1) {
            // Reset the labels message.
            mdh_import_sql(
                mdh_current_plugin_path("assets/model/upgrade-140-3.sql", false)
            );
        }

        // Find threads to process.
        $threads = Madhouse_Messenger_Models_Threads::newInstance()->findAll(
            array(),
            $page,
            $toProcess
        );

        if (empty($threads)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => __("Nothing to do, no threads in database.", mdh_current_plugin_name()),
                    "data" => array(
                        "done" => true
                    )
                )
            );
            exit;
        }

        // Count threads.
        $countThreads = Madhouse_Messenger_Models_Threads::newInstance()->count();

        // Find inbox label.
        $inbox = Madhouse_Messenger_Models_Labels::newInstance()->findByName("inbox");

        // Process every thread for every users in it.
        try {
            foreach ($threads as $t) {
                foreach ($t->getUsers() as $u) {
                    if (!$u->isFake() && !$t->hasLabel($inbox, $u)) {
                        try {
                            $t = Madhouse_Messenger_ThreadActions::addLabel(
                                $t,
                                $u,
                                $inbox
                            );
                        } catch (Madhouse_Messenger_ForbiddenOperationException $e) {
                            // Just ignore.
                        }
                    }
                }
            }
        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(
                array(
                    "status" => "error",
                    "message" => $e->getMessage(),
                    "data" => array(
                        "done" => true
                    )
                )
            );
            exit;
        }

        // Are we done ?
        $isDone = false;

        // Number of items processed.
        $processed = (($page - 1) * $toProcess) + $toProcess;

        if ($processed >= $countThreads) {
            $isDone = true;
            $processed = $countThreads;

            Madhouse_Messenger_Plugin::unregisterUpgrade("1.40.0");
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(
            array(
                "status" => "success",
                "data" => array(
                    "processed" => $processed,
                    "done"      => $isDone,
                )
            )
        );
        exit;
    }
}