<?php

/**
 * Created by PhpStorm.
 * User: mr.incognito
 * Date: 10.11.2018
 * Time: 10:10
 */
class News_model extends MY_Model
{
    const NEWS_TABLE = 'news';
    const PAGE_LIMIT = 5;

    protected $id;
    protected $header;
    protected $short_description;
    protected $text;
    protected $img;
    protected $tags;
    protected $time_created;
    protected $time_updated;

    protected $views;

    protected $comments;
    protected $likes;

    function __construct($id = FALSE)
    {
        parent::__construct();
        $this->class_table = self::NEWS_TABLE;
        $this->set_id($id);
    }

    /**
     * @return string
     */
    public function get_header()
    {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function set_header($header)
    {
        $this->header = $header;
        return $this->_save('header', $header);
    }

    /**
     * @param bool $length
     * @return string
     */
    public function get_short_description($length = false)
    {
        if (!$length || mb_strlen($this->short_description) <= $length) {
            return $this->short_description;
        }

        return mb_substr($this->short_description, 0, $length - 3) . '...';
    }

    /**
     * @param mixed $description
     */
    public function set_short_description($description)
    {
        $this->short_description = $description;
        return $this->_save('short_description', $description);
    }

    /**
     * @return string
     */
    public function get_full_text()
    {
        return $this->text;
    }


    /**
     * @return mixed
     */
    public function get_image()
    {
        return $this->img;
    }

    /**
     * @param mixed $image
     */
    public function set_image($image)
    {
        $this->img = $image;
        return $this->_save('image', $image);
    }

    /**
     * @return string
     */
    public function get_tags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function set_tags($tags)
    {
        $this->tags = $tags;
        return $this->_save('tags', $tags);
    }

    /**
     * @return mixed
     */
    public function get_time_created()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     */
    public function set_time_created($time_created)
    {
        $this->time_created = $time_created;
        return $this->_save('time_created', $time_created);
    }

    /**
     * @param bool $toTimestamp
     * @return int
     */
    public function get_time_updated($toTimestamp = true)
    {
        return $toTimestamp ? strtotime($this->time_updated) : $this->time_updated;
    }

    /**
     * @param mixed $time_updated
     */
    public function set_time_updated($time_updated)
    {
        $this->time_updated = $time_updated;
        return $this->_save('time_updated', $time_updated);
    }

    /**
     * @return News_like_model
     */
    public function get_likes()
    {
        if (is_null($this->likes)) {
            $this->likes = News_like_model::get_all(["news_id = {$this->id}"]);
        }

        return $this->likes;
    }

    /**
     * @return News_comments_model[]
     */
    public function get_comments()
    {
        if (is_null($this->comments)) {
            $this->comments = Comment_model::get_all(["news_id = {$this->id}"]);
        }

        return $this->comments;
    }

    /**
     * @param int $page
     * @param bool|string $preparation
     * @return array
     */
    public static function get_all($preparation = FALSE)
    {

        $CI =& get_instance();

        $_data = $CI->s->from(self::NEWS_TABLE)->many();

        $news_list = self::convert_rows_to_models($_data);

        if ($preparation === FALSE) {
            return $news_list;
        }

        return self::preparation($news_list, $preparation);
    }

    public static function preparation($data, $preparation)
    {

        switch ($preparation) {
            case 'short_info':
                return self::_preparation_short_info($data);
            default:
                throw new Exception('undefined preparation type');
        }
    }

    /**
     * @param News_model[] $data
     * @return array
     */
    private static function _preparation_short_info($data)
    {
        $res = [];
        foreach ($data as $item) {
            $_info = new stdClass();
            $_info->id = (int)$item->get_id();
            $_info->header = $item->get_header();
            $_info->description = $item->get_short_description(300);
            $_info->img = $item->get_image();
            $_info->time = $item->get_time_updated(false);
            $res[] = $_info;
        }
        return $res;
    }
    
    
    public static function create($data){

        $CI =& get_instance();
	    $res = $CI->s->from(self::NEWS_TABLE)->insert($_insert_data)->execute();
	    if(!$res){
	        return FALSE;
        }
	    return new self($CI->s->insert_id);
    }

    public static function get_last($preparation = false, $limit = 3)
    {
        $ci =& get_instance();

        $rows = $ci->s
            ->from(self::NEWS_TABLE)
            ->sortDesc('time_created')
            ->limit($limit)
            ->many();

        $models = self::convert_rows_to_models($rows);

        return $preparation === false ? $models : self::preparation($models, $preparation);
    }

    public static function news_exists($id)
    {
        $ci =& get_instance();

        return !!$ci->s
            ->from(self::NEWS_TABLE)
            ->where("id = $id")
            ->count();
    }

    public static function get_one($id)
    {
        $ci =& get_instance();

        $row = $ci->s
            ->from(self::NEWS_TABLE)
            ->where("id = $id")
            ->one();

        return (new self())->load_data($row);
    }

    public function get_info()
    {
        $info = [
            'id' => $this->id,
            'title' => $this->header,
            'image' => $this->img,
            'content' => $this->text,
            'created_at' => date('d.m.Y H:i:s', strtotime($this->time_created)),
            'likesCount' => count($this->get_likes()),
            'commentsCount' => count($this->get_comments()),
            'comments' => [],
        ];

        foreach ($this->comments as $comment) {
            $info['comments'][] = $comment->get_info();
        }

        return $info;
    }

    public static function get_popular($limit = 3)
    {
        $ci =& get_instance();

        $rows = $ci->s
            ->from(self::NEWS_TABLE)
            ->leftJoin(News_like_model::NEWS_LIKE_TABLE, ['news_like.news_id' => 'news.id'])
            ->groupBy('news.id')
            ->sortDesc('likes_count')
            ->limit($limit)
            ->select([
                'news.id',
                'news.header',
                'news.short_description',
                'news.text',
                'news.img',
                'news.tags',
                'news.time_created',
                'news.time_updated',
                'COUNT(news_like.id) AS likes_count',
            ])->many();

        $models = self::convert_rows_to_models($rows);

        $popular = [];

        foreach ($models as $model) {
            $popular[] = $model->get_info();
        }

        return $popular;
    }

    protected static function convert_rows_to_models($rows)
    {
        $models = [];

        foreach ($rows as $row) {
            $models[] = (new self())->load_data($row);
        }

        return $models;
    }
}
