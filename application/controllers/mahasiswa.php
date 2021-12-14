<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mahasiswa extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // membuat limit method klick/jam 
        // $this->methods['index_get']['limit'] = 5000;
        //PENGATURAN KEY
        // rest_key_name = {wpu-key} dan valunya ada di ambil di dalam database
        // rest_key_name confignya di config/rest.php 

        // PENGATURAN LIMIT 
        // $this->methods['METHOD_NAME']['limit'] = [NUM_REQUESTS_PER_HOUR]
        // $this->methods['METHOD_NAME']['limit'] confignya di config/rest.php 

        // PENGATURAN AUTH / LOGIN 

    }

    public function getnoparam_get()
    {
        // Users from a data store e.g. database
        $users = [
            ['id' => 0, 'name' => 'John', 'email' => 'john@example.com'],
            ['id' => 1, 'name' => 'Jim', 'email' => 'jim@example.com'],
        ];

        $id = $this->get('id');

        if ($id === null) {
            // Check if the users data store contains users
            if ($users) {
                // Set the response and exit
                $this->response($users, 200);
            } else {
                // Set the response and exit
                $this->response(
                    [
                        'status' => false,
                        'message' => 'No users were found'
                    ],
                    404
                );
            }
        } else {
            if (array_key_exists($id, $users)) {
                $this->response($users[$id], 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No such user found'
                ], 404);
            }
        }
    }

    public function index_get()
    {
        $id = $this->get('id');
        if ($id != null) {
            $data = $this->db->get_where("mahasiswa", ['id' => $id]);
            if ($data->num_rows() > 0) {
                $getdata = $this->db->get_where("mahasiswa", ['id' => $id])->row_array();
                $this->response($getdata, RestController::HTTP_OK);
                // $this->response($data->row_array(), 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        } else {
            $users = $this->db->get('mahasiswa')->result();
            $this->response($users, RestController::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = array(
            "nama"    => $this->post('nama', true),
            "nrp"     => $this->post('nrp', true),
            "email"   => $this->post('email', true),
            "jurusan" => $this->post('jurusan', true),
        );
        $insert = $this->db->insert('mahasiswa', $data);
        if ($insert) {
            $this->response($data, RestController::HTTP_OK);
        } else {
            $this->response(
                [
                    "status" => "failed",
                    "message" => "insert failed"
                ],
                500
            );
        }
    }

    public function index_put()
    {
        $id = $this->put("id");
        $data = array(
            "nama"    => $this->put('nama', true),
            "nrp"     => $this->put('nrp', true),
            "email"   => $this->put('email', true),
            "jurusan" => $this->put('jurusan', true),
        );
        $this->db->where("id", $id);
        $update = $this->db->update('mahasiswa', $data);
        if ($update) {
            $this->response($data, RestController::HTTP_OK);
        } else {
            $this->response(
                [
                    "status" => "failed",
                    "message" => "Update failed"
                ],
                502
            );
        }
    }

    public function index_delete()
    {
        $id = $this->delete("id");
        $cek = $this->db->get_where("mahasiswa", ["id" => $id])->num_rows();
        if ($cek > 0) {
            $this->db->where('id', $id);
            $delete = $this->db->delete('mahasiswa');
            if ($delete) {
                $this->response([
                    "status" => "Success",
                    "message" => "Delete Data Success"
                ], 200);
            } else {
                $this->response(
                    [
                        "status" => "failed",
                        "message" => "Delete Failed"
                    ],
                    502
                );
            }
        } else {
            $this->response(
                [
                    "status" => "failed",
                    "message" => "Data Not Found"
                ],
                404
            );
        }
    }
}
