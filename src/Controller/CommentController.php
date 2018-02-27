<?php

namespace Controller;

use App\Controller;
use Model\Comment;

/**
* ¨CommentController
*/
class CommentController extends Controller
{
	
	public function addComment()
	{
		$manager = $this->getDatabase()->getManager(Comment::class);
		$comment = new Comment();
		$comment->setAddedAt(new \DateTime());
		$manager->insert($comment);		
		return $this->redirect("show", ["id" => $comment->getPostId()]);	
	}

	public function updateComment($id)
	{
		$manager = $this->getDatabase()->getManager(Comment::class);
		$comment = $manager->find($id);
		$manager->update($comment);
		return $this->redirect("show", ["id" => $comment->getPostId()]);	
	}

	public function deleteComment($id)
	{
		$manager = $this->getDatabase()->getManager(Comment::class);
		$comment = $manager->find($id);
		$manager->remove($comment);
		return $this->redirect("show", ["id" => $comment->getPostId()]);	
	}
}