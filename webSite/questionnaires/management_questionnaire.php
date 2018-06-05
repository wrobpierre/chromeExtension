<?php 
header('Access-Control-Allow-Origin: *');

ini_set('display_errors',1);
error_reporting(E_ALL);

$adress = "http://163.172.59.102";
//$adress = "http://localhost/chromeExtension";

class all{}

class questionnaire{}

function stripVN($str) {
	$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ|ä)/", 'a', $str);
	$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ|ë)/", 'e', $str);
	$str = preg_replace("/(ì|í|ị|ỉ|ĩ|î|ï)/", 'i', $str);
	$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ô|ö)/", 'o', $str);
	$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|û|ü)/", 'u', $str);
	$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ|ÿ)/", 'y', $str);
	$str = preg_replace("/(đ)/", 'd', $str);

	$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ|Ä)/", 'A', $str);
	$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ|Ë)/", 'E', $str);
	$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ|Î|Ï)/", 'I', $str);
	$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ô|Ö)/", 'O', $str);
	$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
	$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
	$str = preg_replace("/(Đ)/", 'D', $str);
	return $str;
}

function deleteDirectory($dir) {
	if (!file_exists($dir)) {
		return true;
	}
	if (!is_dir($dir)) {
		return unlink($dir);
	}
	foreach (scandir($dir) as $item) {
		if ($item == '.' || $item == '..') {
			continue;
		}
		if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
			return false;
		}
	}
	return rmdir($dir);
}

if (isset($_POST['action'])) {

	$servername = "localhost";
	$username = "root";

	$password = "stageOsaka";
	//$password = "";
	$dbname = "chrome_extension";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['action'] == 'add') {
			/*echo "<pre>";
			var_dump($_POST);
			echo "</pre>";*/
			header("Location: ".$adress."/webSite/questionnaires/questionnaire.php");
			if ( isset($_POST['title']) && isset($_POST['auto_correction']) && isset($_POST['user_email']) && isset($_POST['q']) ) {
				$id = uniqid();

				$target_dir = "../img/quest_img/".$id;
				$target_file = "";

				if ($_POST['user_email'] != "") {
					# code...
					if (mkdir($target_dir, 0777, true)) {
						if ($_FILES["image_uploads"]["name"] != "") {
							$target_file = $target_dir . "/" . basename($_FILES["image_uploads"]["name"]);
							if (!move_uploaded_file($_FILES["image_uploads"]["tmp_name"], $target_file)) {
								header('Location: ' . $_SERVER['HTTP_REFERER']);
								//echo "fail";
							} 
						}

						$stmt = $conn->prepare("INSERT INTO firsturl (url)
							VALUES (:url)");
						$stmt->bindParam(':url', $url);
						$url = $adress."/webSite/questionnaires/questionnaire.php?id=".$id;
						$stmt->execute();

						$stmt = $conn->prepare("INSERT INTO questionnaires (title, statement, link_img, auto_correction, key_user, key_first_url)
							SELECT :title, :statement, :link_img, :auto_correction, (SELECT u.id FROM users u WHERE u.email LIKE :user_email), fu.id FROM firsturl fu WHERE url like :url");
						$stmt->bindParam(':title', $title);
						$stmt->bindParam(':statement', $statement);
						$stmt->bindParam(':link_img', $link_img);
						$stmt->bindParam(':auto_correction', $auto_correction);
						$stmt->bindParam(':url', $url);
						$stmt->bindParam(':user_email', $user_email);
						$title = $_POST['title'];
						$statement = $_POST['statement'];
						$link_img = $target_file;
						$auto_correction = ($_POST['auto_correction'] != 'auto') ? 0 : 1;
						$user_email = $_POST['user_email'];
						$stmt->execute();

						$stmt = $conn->prepare("INSERT INTO questions (question, type_ques, answer, key_questionnaires)
							SELECT :question, :type_ques, :answer, q.id 
							FROM questionnaires q 
							INNER JOIN firsturl fu ON q.key_first_url = fu.id 
							WHERE url like :url");
						$stmt->bindParam(':url', $url);
						$stmt->bindParam(':question', $question);
						$stmt->bindParam(':type_ques', $type_ques);
						$stmt->bindParam(':answer', $answer);

						foreach ($_POST['q'] as $key => $value) {
							$question = $value['question'];

							if ($auto_correction == 1) {
								$type_ques = $value['type_ques'];

								if ($type_ques == 'text') {
									$answer = strtoupper(stripVN($value['answer']));
								}
								elseif ($type_ques == 'number') {
									$answer = $value['answer']."/".$value['particule'];
								}
								elseif ($type_ques == 'interval') {
									$answer = $value['min']."/".$value['max'];
								}
								elseif ($type_ques == 'radio') {
									foreach ($value['choices'] as $k => $v) {
										if (isset($v['answer'])) {
											$answer = $k;
									//echo $answer;
										}
										$question .= "(/=/)".$v['choice'];
										//echo $question.'<br>';
									}
								}
							}
							else {
								$type_ques = "free";
								$answer = null;							
							}

							$stmt->execute();
						}
					}
					else {
						header('Location: ' . $_SERVER['HTTP_REFERER']);
					}
				}
			}
		}
		elseif ($_POST['action'] == 'get_questions_to_edit') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT qtn.title, qtn.statement, qtn.link_img, qtn.auto_correction, q.question, q.type_ques, q.answer, q.id, q.key_questionnaires
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire.php?id='.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
			}
		}
		elseif ($_POST['action'] == 'edit') {
			header("Location: ".$adress."/webSite/questionnaires/questionnaire.php");
			if (isset($_POST['id_questionnaire'])) {

				$stmt = $conn->prepare("UPDATE questionnaires 
					SET title = :title, statement = :statement, auto_correction = :auto_correction 
					WHERE id = :id");
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':statement', $statement);
				$stmt->bindParam(':auto_correction', $auto_correction);
				$stmt->bindParam(':id', $id);
				$title = $_POST['title'];
				$statement = $_POST['statement'];
				$auto_correction = ($_POST['auto_correction'] == 'manuel') ? 0 : 1;
				$id = $_POST['id_questionnaire'];
				$stmt->execute();

				$stmt = $conn->prepare("UPDATE questions 
					SET question = :question, type_ques = :type_ques ,answer = :answer
					WHERE id = :id");
				$stmt->bindParam(':id', $id);
				$stmt->bindParam(':question', $question);
				$stmt->bindParam(':type_ques', $type_ques);
				$stmt->bindParam(':answer', $answer);

				foreach ($_POST['q'] as $key => $value) {
					$id = $key;
					$question = $value['question'];

					if ($auto_correction == 1) {
						$type_ques = $value['type_ques'];

						if ($type_ques == 'text') {
							$answer = strtoupper(stripVN($value['answer']));
						}
						elseif ($type_ques == 'number') {
							$answer = $value['answer']."/".$value['particule'];
						}
						elseif ($type_ques == 'interval') {
							$answer = $value['min']."/".$value['max'];
						}
						elseif ($type_ques == 'radio') {
							foreach ($value['choices'] as $k => $v) {
								if (isset($v['answer'])) {
									$answer = $k;
									//echo $answer;
								}
								$question .= "(/=/)".$v['choice'];
								//echo $question.'<br>';
							}
						}
					}
					else {
						$type_ques = "free";
						$answer = null;							
					}

					$stmt->execute();
				}

				if (isset($_POST['nq'])) {
					$stmt = $conn->prepare("INSERT INTO questions (question, type_ques, answer, key_questionnaires)
						VALUES(:question, :type_ques, :answer, :id)");
					$stmt->bindParam(':question', $question);
					$stmt->bindParam(':type_ques', $type_ques);
					$stmt->bindParam(':answer', $answer);
					$stmt->bindParam(':id', $id);
					$id = $_POST['id_questionnaire'];

					foreach ($_POST['nq'] as $key => $value) {
						$question = $value['question'];

						if ($auto_correction == 1) {
							$type_ques = $value['type_ques'];

							if ($type_ques == 'text') {
								$answer = strtoupper(stripVN($value['answer']));
							}
							elseif ($type_ques == 'number') {
								$answer = $value['answer']."/".$value['particule'];
							}
							elseif ($type_ques == 'interval') {
								$answer = $value['min']."/".$value['max'];
							}
							elseif ($type_ques == 'radio') {
								foreach ($value['choices'] as $k => $v) {
									if (isset($v['answer'])) {
										$answer = $k;
									//echo $answer;
									}
									$question .= "(/=/)".$v['choice'];
									//echo $question.'<br>';
								}
							}
						}
						else {
							$type_ques = "free";
							$answer = null;							
						}

						$stmt->execute();
					}
				}
			}
		}
		elseif ($_POST['action'] == 'delete') {
			if (isset($_POST['url'])) {

				if (deleteDirectory('../img/quest_img/' . explode('?id=', $_POST['url'])[1] )) {
					$stmt = $conn->prepare("DELETE FROM firsturl WHERE url like :url");
					$stmt->bindParam(':url', $url);
					$url = $_POST['url'];
					$stmt->execute();

					echo "The questionnaire has been deleted";
				}
				else {
					echo "An error has been detected. Please try later.";
				}
			}
		}
		elseif ($_POST['action'] == 'delete_question') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("DELETE FROM questions WHERE id like :id");
				$stmt->bindParam(':id', $id);
				$id = $_POST['id'];
				$stmt->execute();

				echo "The question has been deleted";
			}
		}
		elseif ($_POST['action'] == 'all') {
			$stmt = $conn->prepare("SELECT q.title, q.statement, q.link_img, q.auto_correction, fu.url
				FROM questionnaires q 
				INNER JOIN firsturl fu ON q.key_first_url = fu.id");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "all"));
		}
		elseif ($_POST['action'] == 'get_questions') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT (qtn.id)id_questionnaire, qtn.title, qtn.statement, qtn.link_img, q.question, q.image, q.type_ques, q.id, SPLIT_STRING(q.answer, '/', 2)particule
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire.php?id='.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
			}
		}
		elseif ($_POST['action'] == 'get_data_question') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT q.title, q.statement
					FROM firsturl fu
					INNER JOIN questionnaires q ON fu.id = q.key_first_url
					WHERE fu.url LIKE 'http://163.172.59.102/webSite/questionnaires/questionnaire.php?id=".$_POST['id']."'");
				$stmt->execute();

				echo json_encode($stmt->fetchAll());	
			}
		}
		elseif ($_POST['action'] == 'check_title') {
			if (isset($_POST['title'])) {
				$stmt = $conn->prepare("SELECT * FROM questionnaires WHERE title like :title");
				$stmt->bindParam(':title', $title);
				$title = $_POST['title'];
				$stmt->execute();

				echo count($stmt->fetchAll());
			}
		}
		elseif ($_POST['action'] == 'get_user_result') {
			if (isset($_POST['id'])) {
				$stmt = $conn->prepare("SELECT a.key_user, a.key_question, q.question, a.answer
					FROM firsturl fu
					INNER JOIN questionnaires qtn ON fu.id = qtn.key_first_url
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					INNER JOIN answers a ON q.id = a.key_question
					WHERE fu.url LIKE :url
					ORDER BY a.key_user");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire.php?id='.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_CLASS));
			}
		}
		elseif ($_POST['action'] == 'edit_user_result') {
			if (isset($_POST['res'])) {
				header("Location: ".$adress."/webSite/questionnaires/questionnaire.php");

				$stmt = $conn->prepare("UPDATE answers
					SET result = :result
					WHERE key_user = :user AND key_question = :question");
				$stmt->bindParam(':result', $result);
				$stmt->bindParam(':user', $user);
				$stmt->bindParam(':question', $question);

				foreach ($_POST['res'] as $key => $value) {
					$user = $key;
					foreach ($value as $k => $v) {
						$question = $k;
						$result = ($v == 'wrong') ? 0 : 1;

						$stmt->execute();
					}
				}
			}
		}
		elseif ($_POST['action'] == 'already_done') {
			if ( isset($_POST['user']) && isset($_POST['id_questionnaire']) ) {
				$stmt = $conn->prepare("SELECT COUNT(*) 
					FROM answers a
					INNER JOIN questions q ON a.key_question = q.id
					WHERE a.key_user = (SELECT id FROM users u WHERE u.email LIKE :user) and q.key_questionnaires = :id_questionnaire");
				$stmt->bindParam(':user', $user);
				$stmt->bindParam(':id_questionnaire', $id_questionnaire);
				$user = $_POST['user'];
				$id_questionnaire = $_POST['id_questionnaire'];

				$stmt->execute();

				echo count($stmt->fetchAll());
			}
		}
		elseif ($_POST['reset_user_research'] == 'reset_user_research') {
			if ( isset($_POST['user']) && isset($_POST['id_questionnaire']) ) {
				$stmt = $$conn->prepare("DELETE a
					FROM `answers` a
					INNER JOIN questions q ON a.key_question = q.id
					WHERE a.key_user = (SELECT id FROM users u WHERE u.email LIKE :user) and q.key_questionnaires = :id_questionnaire");
				$stmt->bindParam(':user', $user);
				$stmt->bindParam(':id_questionnaire', $id_questionnaire);
				$user = $_POST['user'];
				$id_questionnaire = $_POST['id_questionnaire'];

				$stmt->execute();
			}
		}
	}
	catch(PDOException $e)
	{
		echo "Error: " . $e->getMessage();
	}
	$conn = null;
}

?>