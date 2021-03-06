<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class Spec extends REST_Controller
{

    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['specs_get']['limit'] = 500; // 500 requests per hour per spec/key
        $this->methods['specs_post']['limit'] = 100; // 100 requests per hour per spec/key
        $this->methods['specs_delete']['limit'] = 50; // 50 requests per hour per spec/key
    }

    public function specs_get()
    {
        // Products from a data store e.g. database
        $specs =
        $this->db->get_specs()
        ;

        $id = $this->get('id');

        // If the id parameter doesn't exist return all the specs

        if ($id === null) {
            // Check if the specs data store contains specs (in case the database result returns NULL)
            if ($specs) {
                // Set the response and exit
                $this->response($specs, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            } else {
                // Set the response and exit
                $this->response([
                    'status' => false,
                    'message' => 'No specs were found',
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular spec.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0) {
            // Invalid id, set the response and exit.
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the spec from the array, using the id as key for retrieval.
        // Usually a model is to be used for this.

        $spec = null;

        if (!empty($specs)) {
            foreach ($specs as $key => $value) {
                if (isset($value->id) && $value->id === $id) {
                    $spec = $value;
                }
            }
        }

        if (!empty($spec)) {
            $this->set_response($spec, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            $this->set_response([
                'status' => false,
                'message' => 'User could not be found',
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function specs_post()
    {
        // $this->some_model->update_spec( ... );
        $message = [
            'id' => 100, // Automatically generated by the model
            'name' => $this->post('name'),
            'email' => $this->post('email'),
            'message' => 'Added a resource',
        ];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }

    public function spec_code_get()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0) {
            // Set the response and exit
            $this->response(null, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource',
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }

}
