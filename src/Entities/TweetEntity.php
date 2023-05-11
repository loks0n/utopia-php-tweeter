<?php

namespace App\Entities;

class TweetEntity
{
    private $id;
    private $author;
    private $content;
    private $replyTo;
    private $created;
    private $updated;

    public function __construct($id, $author, $content, $replyTo, $created, $updated)
    {
        $this->id = $id;
        $this->author = $author;
        $this->content = $content;
        $this->replyTo = $replyTo;
        $this->created = $created;
        $this->updated = $updated;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'author' => $this->author,
            'content' => $this->content,
            'replyTo' => $this->replyTo,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }

    // getters
    public function getId()
    {
        return $this->id;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getReplyTo()
    {
        return $this->replyTo;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    // setters
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
    }

    public function setCreated($created)
    {
        $this->created = $created;
    }
}
