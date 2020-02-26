<?php

class Comment_like_model extends MY_Model
{
    const COMMENT_LIKE_TABLE = 'comment_like';

    protected $id;
    protected $comment_id;
    protected $ip;

    protected $comment;

    function __construct($id = false)
    {
        parent::__construct();

        $this->class_table = self::COMMENT_LIKE_TABLE;
        $this->set_id($id);
    }

    public static function make($comment_id)
    {
        $like_exists = self::like_exists($comment_id);
        $like_exists ? self::delete($comment_id) : self::create($comment_id);

        return $like_exists ? 'unlike' : 'like';
    }

    public static function delete($comment_id)
    {
        $ci =& get_instance();

        $ci->s
            ->from(self::COMMENT_LIKE_TABLE)
            ->where('comment_id', $comment_id)
            ->delete()
            ->execute();
    }

    public static function get_all($conditions = [])
    {
        $ci =& get_instance();

        $query = $ci->s
            ->from(self::COMMENT_LIKE_TABLE);

        foreach ($conditions as $condition) {
            $query->where($condition);
        }

        $rows = $query->many();

        $models = self::convert_rows_to_models($rows);

        return $models;
    }

    protected static function like_exists($comment_id)
    {
        $ci =& get_instance();

        return !!$ci->s
            ->from(self::COMMENT_LIKE_TABLE)
            ->where("comment_id = $comment_id")
            ->where('ip', $_SERVER['REMOTE_ADDR'])
            ->count();
    }

    protected static function create($comment_id)
    {
        $ci =& get_instance();

        $ci->s
            ->from(self::COMMENT_LIKE_TABLE)
            ->insert([
                'comment_id' => $comment_id,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ])
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
