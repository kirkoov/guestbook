<?php

namespace KK;

class Comment {
	protected $database, $name, $id, $email, $comment, $submissionDate;
	
	function __construct(\Medoo\Medoo $medoo) {
		$this->database = $medoo;
	}

	public function findAll() {
		$collection = [];
		$comments = $this->database->select('comments', '*', ["ORDER" => ["comments.submissionDate" => "DESC"]]);
		// ["ORDER" => "comments.submissionDate DESC"] doesn't seem to work

		// var_dump($comments);

		if ($comments) {
			foreach ($comments as $array) {
				$comment = new self($this->database);
				$collection[] = $comment
				->setComment($array['comment'])
				->setEmail($array['email'])
				->setId($array['id'])
				->setName($array['name'])
				->setSubmissionDate($array['submissionDate']);
			}
		}
		return $collection;
	}

	public function delCom($id, $em) {
		$del = $this->database->delete('comments', ['id' => $id, 'email' => $em]);
		return $this;
	}

	public function setName($name) {
        $this->name = (string)$name;
        return $this;
    }

    public function setId($id) {
        $this->id = (int)$id;
        return $this;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        } else {
            throw new \InvalidArgumentException('Not a valid email!'); 
        }
        return $this;
    }

    public function setComment($comment) {
        if (mb_strlen($comment) < 3) {
            throw new \InvalidArgumentException('Comment too short!');
		} else {
            $this->comment = $comment;
        }
        return $this;
    }

    protected function setSubmissionDate($date) {
        $this->submissionDate = $date;
		return $this;
    }

    public function getName() {
        return $this->name;
    }
    public function getId() {
        return $this->id;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getComment() {
        return $this->comment;
    }
    public function getSubmissionDate() {
        return $this->submissionDate;
    }

    public function save() {
	  if ($this->getName() && $this->getEmail() && $this->getComment()) {
		$this->setSubmissionDate(date('Y-m-d H:i:s'));
		return $this->database->insert('comments', [
		  'name' => trim($this->getName()),
		  'email' => trim($this->getEmail()),
		  'comment' => trim($this->getComment()),
		  'submissionDate' => $this->getSubmissionDate()
		]);
 	  }
		throw new \Exception("Failed to save!");
	}

}