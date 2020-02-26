<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <style>
        .news-image {
            width: 300px;
            height: 300px;
            object-fit: cover;
        }
        #popular-news {
            margin-top: 5em;
        }
        .news-content {
            padding: 2em 0;
        }
        .news-comments {
            margin-top: 2em;
        }
        #popular-news .news-one {
            margin-top: 3em;
        }
    </style>
</head>
<body>
    <div id="news-view">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>{{ news.title }}</h1>
                    <img :src="news.image" class="news-image">
                    <div class="news-content">{{ news.content }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div><b>Дата создания</b>: {{ news.created_at }}</div>
                    <div><b>К-во лайков</b>: <span>{{ news.likesCount }}</span></div>
                    <div><b>К-во комментариев</b>: <span>{{ news.commentsCount }}</span></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="news-comments">
                        <h2>Комментарии</h2>
                        <div v-for="comment in news.comments">
                            <div>{{ comment.content }}</div>
                            <div><b>Дата создания</b>: {{ comment.created_at }}</div>
                            <div><b>К-во лайков</b>: <span>{{ comment.likesCount }}</span></div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="popular-news">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Последние новости</h2>
                </div>
            </div>
            <div v-for="news in newsAll">
                <div class="news-one">
                    <div class="row">
                        <div class="col-md-12">
                            <h3>{{ news.title }}</h3>
                            <img :src="news.image" class="news-image">
                            <div class="news-content">{{ news.content }}</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div><b>Дата создания</b>: {{ news.created_at }}</div>
                            <div><b>К-во лайков</b>: <span>{{ news.likesCount }}</span></div>
                            <div><b>К-во комментариев</b>: <span>{{ news.commentsCount }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var newsView = new Vue({
            el: '#news-view',
            data() {
                return {
                    news: {}
                };
            },
            mounted() {
                axios.get('/tests/get/<?= $id ?>')
                    .then(response => (this.news = response.data.news));
            }
        });
        var popularNews = new Vue({
            el: '#popular-news',
            data() {
                return {
                    newsAll: {}
                };
            },
            mounted() {
                axios.get('/tests/popular')
                    .then(response => (this.newsAll = response.data.news));
            }
        });
    </script>
</body>
</html>
