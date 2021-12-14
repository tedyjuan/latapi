<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Crudapi extends RestController
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // membuat limit method klick/jam 
        $this->methods['index_get']['limit'] = 5;

        // authetifikasi 
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
            $data = $this->db->get_where("user_details", ['user_id' => $id]);
            if ($data->num_rows() > 0) {
                $getdata = $this->db->get_where("user_details", ['user_id' => $id])->row_array();
                $this->response($getdata, RestController::HTTP_OK);
                // $this->response($data->row_array(), 200);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'No users were found'
                ], 404);
            }
        } else {
            $users = $this->db->get('user_details')->result();
            $this->response($users, RestController::HTTP_OK);
        }
    }

    public function index_post()
    {
        $data = array(
            "username"   => $this->post('username'),
            "first_name" => $this->post('first_name'),
            "last_name"  => $this->post('last_name'),
            "gender"     => $this->post('gender'),
            "password"   => $this->post('password'),
            "status"     => $this->post('status'),
        );
        $insert = $this->db->insert('user_details', $data);
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
        $id = $this->put("user_id");
        $data = array(
            "username"   => $this->put('username'),
            "first_name" => $this->put('first_name'),
            "last_name"  => $this->put('last_name'),
            "gender"     => $this->put('gender'),
            "password"   => $this->put('password'),
            "status"     => $this->put('status'),
        );
        $this->db->where("user_id", $id);
        $update = $this->db->update('user_details', $data);
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
        $id = $this->delete("user_id");
        $cek = $this->db->get_where("user_details", ["user_id" => $id])->num_rows();
        if ($cek > 0) {
            $this->db->where('user_id', $id);
            $delete = $this->db->delete('user_details');
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
