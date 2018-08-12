<?php
date_default_timezone_set('UTC');
session_start();
$userId;

if (!empty($_SESSION['userid'])) {
  require_once('classes/App/AppController.class.php');
  $userId = $_SESSION['userid'];
  $controller = new AppController();
  $taskData = $controller->getData($userId);
  $assignedData = $controller->getAssignedTasks($userId);
  $usersData = $controller->getUsersList();
  $usersDataOptionsHtml = '';
  $isEdit = false;
  $editedId;
  $editedDescription;

  if (!empty($_GET['action'])) {
    switch($_GET['action']) {
      case 'change': {
        $isEdit = true;
        $editedId = $_GET['id'];
        break;
      }

      case 'exit': {
        session_destroy();
        header('Location: ./');
        break;
      }
    }
  }

  if ($isEdit) {
    foreach ($taskData as $task) {
      if ((int) $task['id'] === (int) $editedId) {
        $editedDescription = $task['description'];
      }
    }
  }

  foreach ($usersData as $user) {
    $usersDataOptionsHtml .= '<option value="' . $user['id'] . '">'
      . $user['login']
      . '</option> \n';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>hw-4-2</title>
</head>

<body style="margin: 0 auto; width: 1280px;">
<?php if (empty($userId)): ?>
  <a href="./registration.php" style="padding: 10px; background-color: lightgray;">
    Войти
  </a>

<?php else: ?>
  <h1>Список дел:</h1>
  <div style="display: flex; justify-content: space-around;">
    <form method="POST">
      <?php if ($isEdit): ?>

        <input 
          type="text" 
          name="description" 
          placeholder="Описание задачи"
          value="<?php echo $editedDescription; ?>"
        >
        <input type="hidden" name="id" value="<?php echo $editedId;?>">
        <input type="submit" value="Сохранить">

      <?php else: ?>

        <input type="text" name="new-task" placeholder="Описание задачи">
        <input type="submit" value="Добавить">

      <?php endif; ?>
    </form>


    <form method="POST">
      Сортировать по: 
      <select name="sort-by">

        <option value="date_added">
          Дате добавления
        </option>
        <option value="is_done">
          Статусу
        </option>
        <option value="description">
          Описанию
        </option>

      </select>
      <input type="submit" value="Отсортировать">
    </form>
  </div>

  <table style="width: 100%">
    <thead style="background-color: #bfbfbf;">
      <tr>
        <td>Описание задачи</td>
        <td>Дата добпаления</td>
        <td>Статус</td>
        <td></td>
        <td>Автор</td>
        <td>Ответственный</td>
        <td>Делегировать задачу</td>
      </tr>
    </thead>

    <tbody>
      <?php foreach($taskData as $task): ?>
        <tr>

          <td><?php echo $task['description']; ?></td>
          <td><?php echo $task['date_added']; ?></td>
          <td><?php echo $task['is_done'] ? 'Выполнено' : 'Не выполнено'; ?></td>
          <td>
            <a href="?id=<?php echo $task['id'] ?>&action=change">Изменить</a> 
            <a href="?id=<?php echo $task['id'] ?>&action=done">Выполнить</a>
            <a href="?id=<?php echo $task['id'] ?>&action=delete">Удалить</a>
          </td>
          <td><?php echo $task['author']; ?></td>
          <td><?php echo $task['assigned_user']; ?></td>
          <td>
            <form method="POST">
              <input name="task_id" type="hidden" value="<?php echo $task['id'];?>">
              <select name="assigned_user">
                <?php echo $usersDataOptionsHtml; ?>
              </select>
              <input type="submit" value="Переложить ответственность">
            </form>
          </td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p>Так же, посмотрите, чего Вам поставили в задачи:</p>


  <?php if (!empty($assignedData)): ?>
    <table style="width: 100%">
      <thead style="background-color: #bfbfbf;">
        <tr>
          <td>Описание задачи</td>
          <td>Дата добпаления</td>
          <td>Статус</td>
          <td></td>
          <td>Автор</td>
          <td>Ответственный</td>
        </tr>
      </thead>

      <tbody>
        <?php foreach($assignedData as $task): ?>
          <tr>

            <td><?php echo $task['description']; ?></td>
            <td><?php echo $task['date_added']; ?></td>
            <td><?php echo $task['is_done'] ? 'Выполнено' : 'Не выполнено'; ?></td>
            <td>
              <a href="?id=<?php echo $task['id'] ?>&action=change">Изменить</a> 
              <a href="?id=<?php echo $task['id'] ?>&action=done">Выполнить</a>
              <a href="?id=<?php echo $task['id'] ?>&action=delete">Удалить</a>
            </td>
            <td><?php echo $task['author']; ?></td>
            <td><?php echo $task['assigned_user']; ?></td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
  <p><a href="?action=exit">Выход</a></p>

<?php endif; ?>
</body>
</html>