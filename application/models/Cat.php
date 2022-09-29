<?php

	class Cat extends CI_Model {

		function update($result, $user_id) {

			$timestamp = gmdate("Y-m-d H:i:s");

			if (isset($result['prop_mode'])) {
				$prop_mode = $result['prop_mode'];
			} else {
				// For backward compatibility, SatPC32 does not set propergation mode
				if (isset($result['sat_name'])) {
					$prop_mode = "SAT";
				} else {
					$prop_mode = NULL;
				}
			}

			$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $user_id);
			$query = $this->db->get('cat');

			// Let's keep uplink_freq, downlink_freq, uplink_mode and downlink_mode for backward compatibility
			$data = array(
				'prop_mode' => $prop_mode,
				'power' => $result['power'] ?? 0,
				'sat_name' => $result['sat_name'] ?? NULL,
				'timestamp' => $timestamp,
			);
			if (isset($result['frequency']) && $result['frequency'] != "NULL") {
				$data['frequency'] = $result['frequency'];
			} else {
				$data['frequency'] = $result['uplink_freq'];
			}
			if (isset($result['mode']) && $result['mode'] != "NULL") {
				$data['mode'] = $result['mode'];
			} else {
				$data['mode'] = $result['uplink_mode'];
			}
			if (isset($result['frequency_rx'])) {
				$data['downlink_freq'] = $result['frequency_rx'];
			} else if (isset($result['downlink_freq'])) {
				$data['downlink_freq'] = $result['downlink_freq'];
			} else {
				$data['downlink_freq'] = 0;
			}
			if (isset($result['mode_rx'])) {
				$data['downlink_mode'] = $result['mode_rx'];
			} else if (isset($result['downlink_freq'])) {
				$data['downlink_mode'] = $result['downlink_mode'];
			} else {
				$data['downlink_mode'] = NULL;
			}

			if ($query->num_rows() > 0)
			{
				// Update the record
				foreach ($query->result() as $row)
				{
					$radio_id = $row->id;

					$this->db->where('id', $radio_id);
					$this->db->where('user_id', $user_id);
					$this->db->update('cat', $data);
				}
			} else {
				// Add a new record
				$data['radio'] = $result['radio'];
				$data['user_id'] = $user_id;

				$this->db->insert('cat', $data);
			}
		}

		function status() {
			//$this->db->where('radio', $result['radio']);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$query = $this->db->get('cat');

			return $query;
		}

		function recent_status() {
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->where("timestamp > date_sub(UTC_TIMESTAMP(), interval 15 minute)", NULL, FALSE);

			$query = $this->db->get('cat');
			return $query;
		}

		/* Return list of radios */
		function radios() {
			$this->db->select('id, radio');
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$query = $this->db->get('cat');

			return $query;
		}

		function radio_status($id) {
			$sql = 'SELECT * FROM `cat` WHERE id = ' . $id . ' and user_id =' . $this->session->userdata('user_id');
			return $this->db->query($sql);
		}

		function delete($id) {
			$this->db->where('id', $id);
			$this->db->where('user_id', $this->session->userdata('user_id'));
			$this->db->delete('cat');

			return true;
		}
	}
?>
