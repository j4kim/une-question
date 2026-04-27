<?php

require('init.php');

$data = getData($_GET["id"]);

if (!$data) {
  die("Pas trouvé cette question déso...");
}

$hasAlreadyReplied = false;

foreach($data->replies as $reply) {
  if ($reply->user_id === $user_id) {
    $hasAlreadyReplied = true;
    break;
  }
}

$repliesCount = count($data->replies);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $data->question ?></title>
  <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
  <style>
    table input, table button {
      margin-bottom: 0 !important;
    }
    table button[name=delete] {
      width: auto;
      padding-top: 2px;
      padding-bottom: 4px;
    }
    input {
      min-width: 100px;
    }
    tr.reply-row td.reply-cell {
      display: flex;
      align-items: center;
    }
    td.reply-cell .reply {
      flex-grow: 1;
    }
    td.reply-cell .action {
      width: auto;
    }
    td.reply-cell input.reply {
      border-top-right-radius: 0;
      border-bottom-right-radius: 0;
    }
    td.reply-cell button.action#send-btn {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
    }
    @media screen and (max-width: 420px) {
      :root {
        --spacing: 0.5rem;
        --font-size: 14px;
        --form-element-spacing-vertical: 0.5rem;
        --form-element-spacing-horizontal: 0.7rem;
      }
    }
  </style>
</head>
<body>
<main class="container">
  <form action="reply.php" method="post">
    <input type="hidden" name="question_id" value="<?= $data->question_id ?>">
    <?= $hasAlreadyReplied ? '<small>Vous avez déjà répondu</small>' : '' ?>
    <table>
      <thead>
        <tr>
          <th>
            <?= $repliesCount . " participant" . ($repliesCount > 1 ? 's' : '') ?>
          </th>
          <th>
            <?= $data->question ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php if (!$hasAlreadyReplied) { ?>
          <tr class="reply-row">
            <td>
              <input
                type="text"
                placeholder="Votre nom"
                name="name"
                value="<?= @$_COOKIE['user_name'] ?>"
              >
            </td>
            <td class="reply-cell">
              <input class="reply" type="text" placeholder="Votre réponse" name="reply">
              <button class="action" id="send-btn" type="submit">Envoyer</button>
            </td>
          </tr>
        <?php } ?>
        <?php foreach ($data->replies as $reply) { ?>
          <tr>
            <td><?= $reply->name ?></td>
            <td class="reply-cell">
              <div class="reply">
                <?= $reply->reply ?>
              </div>
              <?php if ($reply->user_id === $user_id) { ?>
                <button
                  class="action secondary outline"
                  type="submit"
                  name="delete"
                  value="<?= $reply->created_at ?>"
                >
                  x
                </button>
              <?php } ?>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
  <script>
    var form = document.querySelector('form')
    form.addEventListener("submit", function(e){
      var deleting = this.delete && this.delete.value
      var inserting = this.name.value && this.reply.value
      if (!(deleting || inserting)) {
        alert("Veuillez remplir votre nom et votre réponse")
        e.preventDefault()
      }
    });
  </script>
</main>
</body>
</html>
