<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 21:36
 */
class Tests extends MY_Controller
{
    protected $response_data;

    public function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->load->model('news_model');
        $this->load->model('news_like_model');
        $this->load->model('comment_model');
        $this->load->model('comment_like_model');

        if (ENVIRONMENT === 'production')
        {
            die('Access denied!');
        }
    }

    // костыль для тестов)
    public function index()
    {
        $this->get_last_news();
    }

    public function get_last_news()
    {
        return $this->response_success(['news' => News_model::get_last('short_info'),'patch_notes' => []]);
    }

    public function get_comments(int $news_id){ // or can be $this->input->post('news_id')
        // for example: get all comments by api request :)
        return $this->response_error('not_implemented');
    }

    public function like(int $news_id)
    {
        if (!News_model::news_exists($news_id)) {
            return $this->response([], 404);
        }

        return $this->response_success(['result' => News_like_model::make($news_id)]);
    }

    public function comment(int $news_id)
    {
        if (!News_model::news_exists($news_id)) {
            return $this->response([], 404);
        }

        $content = (string) $_POST['content'];
        Comment_model::create($news_id, $content);

        return $this->response_success();
    }

    public function view(int $news_id)
    {
        if (!News_model::news_exists($news_id)) {
            return $this->response([], 404);
        }

        $this->load->view('news/view', ['id' => $news_id]);
    }

    public function get(int $news_id)
    {
        if (!News_model::news_exists($news_id)) {
            return $this->response([], 404);
        }

        return $this->response_success(['news' => News_model::get_one($news_id)->get_info()]);
    }

    public function popular()
    {
       return $this->response_success(['news' => News_model::get_popular()]);
    }
}
