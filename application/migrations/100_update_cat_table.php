<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
   Update CAT table
*/

class Migration_update_cat_table extends CI_Migration {

    public function up()
    {
        if ($this->db->table_exists('cat')) {
            $this->dbforge->drop_column('cat', 'uplink_freq');
            $this->dbforge->drop_column('cat', 'uplink_mode');
            $this->db->query("ALTER TABLE cat RENAME COLUMN downlink_mode TO mode_rx");
            $this->db->query("ALTER TABLE cat RENAME COLUMN downlink_freq TO frequency_rx");
        }
    }

    public function down()
    {
        if ($this->db->table_exists('cat')) {
            $this->db->query("ALTER TABLE cat RENAME COLUMN mode_rx TO downlink_mode");
            $this->db->query("ALTER TABLE cat RENAME COLUMN frequency_rx TO downlink_freq");

            if (!$this->db->field_exists('uplink_freq', 'cat')) {
                $fields = array(
                    'uplink_freq bigint(13) DEFAULT NULL AFTER `downlink_freq`',
                    'uplink_mode varchar(255) DEFAULT NULL AFTER `downlink_mode`',
                );
                $this->dbforge->add_column('cat', $fields);
            }
        }
    }
}
