<?php

class Comment_model extends MY_Model
{
    const COMMENT_TABLE = 'comment';

    protected $id;
    protected $news_id;
    protected $content;
    protected $created_at;

    protected $likes;

    function __construct($id = false)
    {
        parent::__construct();

        $this->class_table = self::COMMENT_TABLE;
        $this->set_id($id);
    }

    public function get_likes()
    {
        if (is_null($this->likes)) {
            $this->likes = Comment_like_model::get_all(["comment_id = {$this->id}"]);
        }

        return $this->likes;
    }

    public static function comment_exists($id)
    {
        $ci =& get_instance();

        return !!$ci->s
            ->from(self::COMMENT_TABLE)
            ->where("id = $id")
            ->count();
    }

    public static function create($news_id, $content)
    {
        $ci =& get_instance();

        $ci->s
            ->from(self::COMMENT_TABLE)
            ->insert([
                'news_id' => $news_id,
                'content' => $content,
            ])->execute();
    }

    public static function delete($id)
    {
        $ci =& get_instance();

        Comment_like_model::delete($id);

        $ci->s
            ->from(self::COMMENT_TABLE)
            ->where('id', $id)
            ->delete()
            ->execute();
    }

    public static function get_all($conditions = [])
    {
        $ci =& get_instance();

        $query = $ci->s
            ->from(self::COMMENT_TABLE);

        foreach ($conditions as $condition) {
            $query->where($condition);
        }

        $rows = $query->many();

        $models = self::convert_rows_to_models($rows);

        return $models;
    }

    public function get_info()
    {
        $info = [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => date('d.m.Y H:i:s', strtotime($this->created_at)),
            'likesCount' => count($this->get_likes()),
        ];

        return $info;
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
