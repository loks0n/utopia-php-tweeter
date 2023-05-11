<?php

namespace App\Services;

use App\Entities\TweetEntity;
use App\Repos\TweetRepository;

class TweetService
{
    private $repository;

    public function __construct(TweetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTweet($author, $content, $replyTo = null)
    {

        $tweets = $this->repository->loadTweets();

        $id = count($tweets);
        $tweet = new TweetEntity($id, $author, $content, $replyTo, time(), time());

        $tweets[$id] = $tweet;
        $this->repository->saveTweets($tweets);

        return $tweet;
    }

    public function getTweets($includeReplies = false)
    {
        $tweets = $this->repository->loadTweets();

        if (!$includeReplies) {
            $tweets = array_filter($tweets, function ($tweet) {
                return $tweet->getReplyTo() === null;
            });
        }

        return $tweets;
    }

    public function getTweetById($id)
    {
        $tweets = $this->repository->loadTweets();
        return $tweets[$id] ?? null;
    }


    public function updateTweet($id, $author, $content)
    {
        $tweets = $this->repository->loadTweets();

        if (!isset($tweets[$id])) {
            return null;
        }

        $tweet = $tweets[$id];
        $tweet->setAuthor($author);
        $tweet->setContent($content);
        $tweet->setUpdated(time());

        $tweets[$id] = $tweet;
        $this->repository->saveTweets($tweets);

        return $tweet;
    }

    public function deleteTweet($id)
    {
        $tweets = $this->repository->loadTweets();

        if (!isset($tweets[$id])) {
            return false;
        }

        unset($tweets[$id]);
        $this->repository->saveTweets($tweets);

        return true;
    }
}
