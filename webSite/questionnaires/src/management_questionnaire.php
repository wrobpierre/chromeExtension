<?php 
header('Access-Control-Allow-Origin: *');

ini_set('display_errors',1);
error_reporting(E_ALL);

$adress = "http://163.172.59.102";

class all{}

class questionnaire{}

/**
 * The function “stripVN” replaces every letters with an accent by one without. 
 * We use it to convert answers to "text" questions when creating a questionnaire.
 *
 * @param string - $str, A string with accents
 *
 * @return string - $str, A string without accents
 */
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

/**
 * The function “deleteDirectory” allows to delete a directory with all its contents.
 * 
 * @param string - $str, The path of the directory
 *
 * @return bool - return true if the directory was deleted
 */
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

	/** @var string The name of the server where the database is stored */
	$servername = "localhost";
	/** @var string The login of the database */
	$username = "root";
	/** @var string The password of the database */
	$password = "stageOsaka";
	/** @var string The name of the database */
	$dbname = "chrome_extension";

	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    	// Set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if ($_POST['action'] == 'add') {
			header("Location: ".$adress."/webSite/questionnaires/questionnaire");
			if ( isset($_POST['title']) && isset($_POST['auto_correction']) && isset($_POST['user_email']) && isset($_POST['q']) ) {
				$id = uniqid();

				$target_dir = "../../img/quest_img/".$id;
				$target_file = "";
				if ($_POST['user_email'] != "") {
					// Create a directory to store the questionnaire's pictures
					if (mkdir($target_dir, 0777, true)) {
						// If there is an image 
						if ($_FILES["questionnaire"]["name"] != "") {
							$target_file = $target_dir . "/" . basename($_FILES["questionnaire"]["name"]);
							// Save the image in the questionnaire's directory
							if (!move_uploaded_file($_FILES["questionnaire"]["tmp_name"], $target_file)) {
								// If saving fail redirects to the questionnaire's creation page
								header('Location: ' . $_SERVER['HTTP_REFERER']);
								exit;
							}
							chmod($target_file, 0777);
						}
						else {
							$target_file = null;
						}

						$stmt = $conn->prepare("INSERT INTO firsturl (url)
							VALUES (:url)");
						$stmt->bindParam(':url', $url);
						$url = $adress."/webSite/questionnaires/questionnaire-".$id;
						$stmt->execute();

						// Send a request to the database to back up a new questionnaire
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
						$link_img = substr($target_file, 3);
						$auto_correction = ($_POST['auto_correction'] != 'auto') ? 0 : 1;
						$user_email = $_POST['user_email'];
						$stmt->execute();

						// Send a request to the database to back up the new questionnaire’s questions
						$stmt = $conn->prepare("INSERT INTO questions (question, type_ques, answer, image, key_questionnaires)
							SELECT :question, :type_ques, :answer, :image, q.id 
							FROM questionnaires q 
							INNER JOIN firsturl fu ON q.key_first_url = fu.id 
							WHERE url like :url");
						$stmt->bindParam(':url', $url);
						$stmt->bindParam(':question', $question);
						$stmt->bindParam(':type_ques', $type_ques);
						$stmt->bindParam(':answer', $answer);
						$stmt->bindParam(':image', $image);

						foreach ($_POST['q'] as $key => $value) {
							// If there is an image 
							if ($_FILES["question"]["name"][$key] != "") {
								$target_file = $target_dir . "/" . basename($_FILES["question"]["name"][$key]);
								// Save the image in the questionnaire's directory
								if (!move_uploaded_file($_FILES["question"]["tmp_name"][$key], $target_file)) {
									// If saving fail redirects to the questionnaire's creation page
									header('Location: ' . $_SERVER['HTTP_REFERER']);
								}
								else {
									$image = substr($target_file, 3);	
								}
								chmod($target_file, 0777);
							}
							else {
								$image = null;
							}
							$question = $value['question'];

							// Check the questionnaire's type
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
										}
										$question .= "(/=/)".$v['choice'];
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
				// Send a request to the database to get the datas necessary to edit a questionnaire
				$stmt = $conn->prepare("SELECT qtn.title, qtn.statement, qtn.link_img, qtn.auto_correction, qtn.key_user, q.question, q.type_ques, q.image, q.answer, q.id, q.key_questionnaires
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire-'.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
			}
		}
		elseif ($_POST['action'] == 'edit') {
			header("Location: ".$adress."/webSite/questionnaires/questionnaire");
			if (isset($_POST['id_questionnaire']) && isset($_POST['param_id'])) {

				$target_dir = "../../img/quest_img/".$_POST['param_id'];
				$target_file = "";

				// Check if there is a new image and compare it to the questionnaire's current image
				if ($_FILES["questionnaire"]["name"] != "" && $_FILES["questionnaire"]["name"] != $_POST['current_img']) {
					// Check if there is already a saved image 
					if ($_POST['current_img'] != "") {
						// If yes remove the current image and replace it by the new
						if ( unlink($target_dir . "/" . $_POST['current_img']) ) {
							$target_file = $target_dir . "/" . basename($_FILES["questionnaire"]["name"]);
							if (!move_uploaded_file($_FILES["questionnaire"]["tmp_name"], $target_file)) {
								// If saving fail redirects to the questionnaire's edit page
								header('Location: ' . $_SERVER['HTTP_REFERER']);
								exit;
							}
							chmod($target_file, 0777);
						}
					}
					// If not just save the image
					else {
						$target_file = $target_dir . "/" . basename($_FILES["questionnaire"]["name"]);
						if (!move_uploaded_file($_FILES["questionnaire"]["tmp_name"], $target_file)) {
							// If saving fail redirects to the questionnaire's edit page
							header('Location: ' . $_SERVER['HTTP_REFERER']);
							exit;
						}
						chmod($target_file, 0777);
					}
				}
				elseif ($_POST['current_img'] != "") {
					$target_file = $target_dir . "/" . $_POST['current_img'];
				}
				else {
					$target_file = null;
				}

				// Send a request to the database to apply all the questionnaire’s changes
				$stmt = $conn->prepare("UPDATE questionnaires 
					SET title = :title, statement = :statement, auto_correction = :auto_correction, link_img = :link_img
					WHERE id = :id");
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':statement', $statement);
				$stmt->bindParam(':auto_correction', $auto_correction);
				$stmt->bindParam(':link_img', $link_img);
				$stmt->bindParam(':id', $id);
				$title = $_POST['title'];
				$statement = $_POST['statement'];
				$auto_correction = ($_POST['auto_correction'] == 'manuel') ? 0 : 1;
				$link_img = substr($target_file, 3);
				$id = $_POST['id_questionnaire'];
				$stmt->execute();

				// Send a request to the database to apply the changes to the questions in the questionnaire that we just changed
				$stmt = $conn->prepare("UPDATE questions 
					SET question = :question, type_ques = :type_ques ,answer = :answer, image = :image
					WHERE id = :id");
				$stmt->bindParam(':id', $id);
				$stmt->bindParam(':question', $question);
				$stmt->bindParam(':type_ques', $type_ques);
				$stmt->bindParam(':answer', $answer);
				$stmt->bindParam(':image', $image);

				foreach ($_POST['q'] as $key => $value) {
					// Check if there is a new image and compare it to the question's current image
					if ($_FILES["question"]["name"][$key] != "" && $_FILES["question"]["name"][$key] != $value['current_img']) {
						// Check if there is already a saved image 
						if ($value['current_img'] != "") {
							// If yes remove the current image and replace it by the new
							if ( unlink($target_dir . "/" . $value['current_img']) ) {
								$target_file = $target_dir . "/" . basename($_FILES["question"]["name"][$key]);
								if (!move_uploaded_file($_FILES["question"]["tmp_name"][$key], $target_file)) {
									// If saving fail redirects to the questionnaire's edit page
									header('Location: ' . $_SERVER['HTTP_REFERER']);
									exit;
								}
								chmod($target_file, 0777);
							}
						}
						// If not just save the image
						else {
							$target_file = $target_dir . "/" . basename($_FILES["question"]["name"][$key]);
							if (!move_uploaded_file($_FILES["question"]["tmp_name"][$key], $target_file)) {
								// If saving fail redirects to the questionnaire's edit page
								header('Location: ' . $_SERVER['HTTP_REFERER']);
								exit;
							}
							chmod($target_file, 0777);
						}
					}
					elseif ($value['current_img'] != "") {
						$target_file = $target_dir . "/" . $value['current_img'];
					}
					else {
						$target_file = null;
					}

					$image = substr($target_file, 3);
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
								}
								$question .= "(/=/)".$v['choice'];
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
					// Send a request to the database to back up the new questions add a questionnaire that has just been changed
					$stmt = $conn->prepare("INSERT INTO questions (question, type_ques, answer, image, key_questionnaires)
						VALUES(:question, :type_ques, :answer, :image, :id)");
					$stmt->bindParam(':question', $question);
					$stmt->bindParam(':type_ques', $type_ques);
					$stmt->bindParam(':answer', $answer);
					$stmt->bindParam(':image', $image);
					$stmt->bindParam(':id', $id);
					$id = $_POST['id_questionnaire'];

					foreach ($_POST['nq'] as $key => $value) {
						// If there is an image 
						if ($_FILES["new_question"]["name"][$key] != "") {
							$target_file = $target_dir . "/" . basename($_FILES["new_question"]["name"][$key]);
							// Save the image in the questionnaire's directory
							if (!move_uploaded_file($_FILES["new_question"]["tmp_name"][$key], $target_file)) {
								// If saving fail redirects to the questionnaire's edit page
								header('Location: ' . $_SERVER['HTTP_REFERER']);
							}
							else {
								$image = $target_file;	
							}
							chmod($target_file, 0777);
						}
						else {
							$image = null;
						}

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
									}
									$question .= "(/=/)".$v['choice'];
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
				// Delete the directory containing questionnaire’s images
				if (deleteDirectory('../../img/quest_img/' . explode('-', $_POST['url'])[1] )) {
					//if it’s a success, send a request to the database to delete the firsturl
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
				// Send a request to the database to delete a questions when the user edit a questionnaire
				$stmt = $conn->prepare("DELETE FROM questions WHERE id like :id");
				$stmt->bindParam(':id', $id);
				$id = $_POST['id'];
				$stmt->execute();

				echo "The question has been deleted";
			}
		}
		elseif ($_POST['action'] == 'all') {
			// Send a request to the database to get the datas necessary to generate the page “questionnaire.php” when there isn’t parameter in the url
			$stmt = $conn->prepare("SELECT q.title, q.statement, q.link_img, q.auto_correction, q.key_user, fu.url
				FROM questionnaires q 
				INNER JOIN firsturl fu ON q.key_first_url = fu.id");
			$stmt->execute();

			echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "all"));
		}
		elseif ($_POST['action'] == 'get_questions') {
			if (isset($_POST['id'])) {
				// Send a request to the database to get the datas necessary to generate the page of a questionnaire
				$stmt = $conn->prepare("SELECT (qtn.id)id_questionnaire, qtn.title, qtn.statement, qtn.link_img, q.question, q.image, q.type_ques, q.id, SPLIT_STRING(q.answer, '/', 2)particule
					FROM questionnaires qtn 
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					WHERE qtn.key_first_url = (SELECT id FROM firsturl WHERE url LIKE :url)");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire-'.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS, "questionnaire"));
			}
		}
		elseif ($_POST['action'] == 'get_data_question') {
			if (isset($_POST['id'])) {
				// Send a request to the database to get the title, the statement and the questionnaire’s question. This request is call in script.js
				$stmt = $conn->prepare("SELECT qts.title, qts.statement, q.question
					FROM firsturl fu
					INNER JOIN questionnaires qts ON fu.id = qts.key_first_url
					INNER JOIN questions q ON qts.id = q.key_questionnaires
					WHERE fu.url LIKE 'http://163.172.59.102/webSite/questionnaires/questionnaire-".$_POST['id']."'");
				$stmt->execute();

				echo json_encode($stmt->fetchAll());	
			}
		}
		elseif ($_POST['action'] == 'check_title') {
			if (isset($_POST['title'])) {
				// Send a request to the database to check if the title for a questionnaire is already taken.
				// This request is call in the pages “add_questionnaire.php” and “edit_questionnaire.php”
				$stmt = $conn->prepare("SELECT * FROM questionnaires WHERE title like :title");
				$stmt->bindParam(':title', $title);
				$title = $_POST['title'];
				$stmt->execute();

				echo count($stmt->fetchAll());
			}
		}
		elseif ($_POST['action'] == 'get_user_result') {
			if (isset($_POST['id'])) {
				// Send a request to the database to get the answers of the users on a questionnaire. 
				// This request is call in the page “edit_results.js” to generate the page’s content
				$stmt = $conn->prepare("SELECT a.key_user, a.key_question, q.question, a.answer, a.result
					FROM firsturl fu
					INNER JOIN questionnaires qtn ON fu.id = qtn.key_first_url
					INNER JOIN questions q ON qtn.id = q.key_questionnaires
					INNER JOIN answers a ON q.id = a.key_question
					WHERE fu.url LIKE :url
					ORDER BY a.key_user");
				$stmt->bindParam(':url', $url);
				$url = $adress.'/webSite/questionnaires/questionnaire-'.$_POST['id'];
				$stmt->execute();

				echo json_encode($stmt->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_CLASS));
			}
		}
		elseif ($_POST['action'] == 'edit_user_result') {
			if (isset($_POST['res'])) {
				header("Location: ".$adress."/webSite/questionnaires/questionnaire");
				// Send a request to the database to change the users’ results
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
				// Send a request to the database to check if a user has already done a questionnaire
				$stmt = $conn->prepare("SELECT * 
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
		elseif ($_POST['action'] == 'reset_user_research') {
			if ( isset($_POST['user']) && isset($_POST['id_questionnaire']) && isset($_POST['param'])) {
				// Send a request to the database to delete all the answers of a user to a questionnaire
				$stmt = $conn->prepare("DELETE a
					FROM `answers` a
					INNER JOIN questions q ON a.key_question = q.id
					WHERE a.key_user = (SELECT id FROM users u WHERE u.email LIKE :user) and q.key_questionnaires = :id_questionnaire");
				$stmt->bindParam(':user', $user);
				$stmt->bindParam(':id_questionnaire', $id_questionnaire);
				$user = $_POST['user'];
				$id_questionnaire = $_POST['id_questionnaire'];
				$stmt->execute();

				// Send a request to the database to delete all the sites a user has used to resolve the questionnaire
				$stmt = $conn->prepare("DELETE 
					FROM sites
					WHERE sites.key_user = (SELECT id FROM users u WHERE u.email LIKE :user) and sites.key_first_url = 
					(SELECT id FROM firsturl fu WHERE fu.url LIKE :url)");
				$stmt->bindParam(':user', $user);
				$stmt->bindParam(':url', $url);
				$user = $_POST['user'];
				$url = $adress.'/webSite/questionnaires/questionnaire-'.$_POST['param'];
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