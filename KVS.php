<?php
namespace Stanford\KVS;

class KVS extends \ExternalModules\AbstractExternalModule
{
    const ENC_PREFIX = "KVS Encrypted: ";

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

        // Remove prepended KVS prefix
        $value = preg_replace("/^" . self::ENC_PREFIX . "/", "", $value);

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

        // Append a KVS__ prefix to the key so we know it was processed by the KVS module
        $value = self::ENC_PREFIX . $value;

        $this->setProjectSetting($key,$value,$project_id);
        return true;
    }

    private static function encrypt($value) {
        return encrypt($value);
    }

    private static function decrypt($value) {
        return decrypt($value);
    }

    /**
     * Does the value start with the ENC_PREFIX
     * @param $value
     * @return bool
     */
    public function isEncrypted($value) {
        if (strpos('x'.$value, self::ENC_PREFIX, 1) === 1) {
            return true;
        } else {
            return false;
        }
    }

}