<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_rename_cat_columns extends CI_Migration {
    public function up()
    {
        if ($this->db->field_exists('downlink_freq', 'cat')) {
            $fields = array(
               'downlink_freq' => array(
                  'name' => 'frequency_rx',
                  'type' => 'BIGINT',
               ),
            );
            $this->dbforge->modify_column('cat', $fields);
        }
        if ($this->db->field_exists('downlink_mode', 'cat')) {
            $fields = array(
               'downlink_mode' => array(
                  'name' => 'mode_rx',
                  'type' => 'VARCHAR(255)',
               ),
            );
            $this->dbforge->modify_column('cat', $fields);
        }
        if ($this->db->field_exists('uplink_freq', 'cat')) {
            $this->dbforge->drop_column('cat', 'uplink_freq');
        }
        if ($this->db->field_exists('uplink_mode', 'cat')) {
            $this->dbforge->drop_column('cat', 'uplink_mode');
        }
    }

    public function down()
    {
        if ($this->db->field_exists('frequency_rx', 'cat')) {
            $fields = array(
               'frequency_rx' => array(
                  'name' => 'downlink_freq',
                  'type' => 'BIGINT',
               ),
            );
            $this->dbforge->modify_column('cat', $fields);
        }
        if ($this->db->field_exists('mode_rx', 'cat')) {
            $fields = array(
               'mode_rx' => array(
                  'name' => 'downlink_mode',
                  'type' => 'VARCHAR(255)',
               ),
            );
            $this->dbforge->modify_column('cat', $fields);
        }
        if (!$this->db->field_exists('uplink_freq', 'cat')) {
            $fields = array(
               'uplink_freq' => array(
                  'type' => 'BIGINT',
                  'null'    => TRUE,
                  'default' => NULL,
               ),
            );
            $this->dbforge->add_column('cat', $fields);
        }
        if (!$this->db->field_exists('uplink_mode', 'cat')) {
            $fields = array(
               'uplink_mode' => array(
                  'type' => 'VARCHAR(255)',
                  'null'    => TRUE,
                  'default' => NULL,
               ),
            );
            $this->dbforge->add_column('cat', $fields);
        }
    }
}
