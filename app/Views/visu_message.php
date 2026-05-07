<?= $this->extend('layout') ?>

<?= $this->section('contenu') ?>
<?php
$policeTitre = $message['POLICETITRE'];
$policeContenu = $message['POLICECONTENU'];
$tailleTitre = $message['TAILLETITRE'];
$tailleContenu =$message['TAILLECONTENU'];
$fond = esc($message['FOND']->getClientPath());

if ($message['ALIGNEMENT']=="droite"){
    $alignement="right";
} else if ($message['ALIGNEMENT']=="gauche"){
    $alignement="left";
} else {
    $alignement="center";
}
?>

<style>
    .article {
        margin: 10px;
        padding: 1em;
        border-radius: 10px;
        border: solid;
        border-width: 2px;
        border-color: black;
        background-color: #eee;

    }

    .titre {
        font-family: <?= $policeTitre ?>;
        font-size: <?=$tailleTitre/10?>rem;
        text-align: <?= $alignement ?>;
    }

    .contenu {
        font-family: <?= $policeContenu ?>;
        font-size: <?=$tailleContenu/10?>rem;
        text-align: <?= $alignement ?>;
    }
</style>

<h1>Visualisation des message de <?= $commune['NOM'] ?></h1>

<!-- les bouton suivant et précédent ne fonctionne pas , à modifier -->
<a class="bouton left" href='#'> Précédent </a> <a class="bouton right" href='#'> Suivant </a>

<article class="article">
    <h2 class="titre"> <?= $message['TITRE'] ?></h2>
    <p class="contenu"> <?= $message['CONTENU'] ?></p>
    <img class="fit_picture" src= <?=$fond?> />

</article>
<?= $this->endSection() ?>