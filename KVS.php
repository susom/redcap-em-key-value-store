<?php
namespace Stanford\KVS;

class KVS extends \ExternalModules\AbstractExternalModule
{

    /**
     * getValue
     * @param $project_id
     * @param $key
     * @return bool|string
     */
    public function getValue($project_id, $key) {
        if (empty($project_id) or intval($project_id) < 1) {
            // Invalid project_id
            return false;
        }

        if  (empty($key)) {
            // Invalid key
            return false;
        }

        $value = $this->getProjectSetting($key,$project_id);
        return self::decrypt($value);
    }

    /**
     * setValue
     * @param $project_id
     * @param $key
     * @param $value
     * @return bool
     */
    public function setValue($project_id, $key, $value) {

        if (empty($project_id) or intval($project_id) < 1) {
            // Invalid project_id
            return false;
        }

        if  (empty($key)) {
            // Invalid key
            return false;
        }

        $value = self::encrypt($value);
        $this->setProjectSetting($key,$value,$project_id);
        return true;
    }

    private static function encrypt($value) {
        return encrypt($value);
    }

    private static function decrypt($value) {
        return decrypt($value);
    }
}