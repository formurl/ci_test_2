<?php

class Comment extends MY_Controller
{
    protected $response_data;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('comment_model');
        $this->load->model('comment_like_model');

        if (ENVIRONMENT === 'production') {
            die('Access denied!');
        }
    }

    public function delete(int $id)
    {
        if (!Comment_model::comment_exists($id)) {
            return $this->response([], 404);
        }

        Comment_model::delete($id);

        return $this->response_success();
    }

    public function like(int $id)
    {
        if (!Comment_model::comment_exists($id)) {
            return $this->response([], 404);
        }

        return $this->response_success(['result' => Comment_like_model::make($id)]);
    }
}
