<?php

class News_like_model extends MY_Model
{
    const NEWS_LIKE_TABLE = 'news_like';

    protected $id;
    protected $news_id;
    protected $ip;

    protected $news;

    function __construct($id = false)
    {
        parent::__construct();

        $this->class_table = self::NEWS_LIKE_TABLE;
        $this->set_id($id);
    }

    public static function make($news_id)
    {
        $like_exists = self::like_exists($news_id);
        $like_exists ? self::delete($news_id) : self::create($news_id);

        return $like_exists ? 'unlike' : 'like';
    }

    public static function get_all($conditions = [])
    {
        $ci =& get_instance();

        $query = $ci->s
            ->from(self::NEWS_LIKE_TABLE);

        foreach ($conditions as $condition) {
            $query->where($condition);
        }

        $rows = $query->many();

        $models = self::convert_rows_to_models($rows);

        return $models;
    }
    
    protected static function like_exists($news_id)
    {
        $ci =& get_instance();

        return !!$ci->s
            ->from(self::NEWS_LIKE_TABLE)
            ->where("news_id = $news_id")
            ->where('ip', $_SERVER['REMOTE_ADDR'])
            ->count();
    }

    protected static function create($news_id)
    {
        $ci =& get_instance();

        $ci->s
            ->from(self::NEWS_LIKE_TABLE)
            ->insert([
                'news_id' => $news_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ])
            ->execute();
    }

    protected static function delete($news_id)
    {
        $ci =& get_instance();

        $ci->s
            ->from(self::NEWS_LIKE_TABLE)
            ->where('news_id', $news_id)
            ->delete()
            ->execute();
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
