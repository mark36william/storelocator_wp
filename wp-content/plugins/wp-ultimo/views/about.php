<?php
/**
 * About view.
 *
 * @since 2.0.0
 */
?>

<style>
.wu-about-content a {
	text-decoration: none;
	font-weight: 500;
	color: #333;
}

.wu-about-content a::after {
	content: "↖︎";
	transform: scale(-0.7, 0.7);
	display: inline-block;
}
</style>

<a class="wu-fixed wu-inline-block wu-bottom-0 wu-left-1/2 wu-transform wu--translate-x-1/2 wu-bg-white wu-p-4 wu-rounded-full wu-shadow wu-m-4 wu-no-underline wu-z-10 wu-border-gray-300 wu-border-solid wu-border" href="<?php echo esc_attr(network_admin_url()); ?>">
  <?php _e('&larr; Back to the Dashboard', 'wp-ultimo'); ?>
</a>

<div id="wp-ultimo-wrap" class="wrap wu-about-content">

  <div style="max-width: 730px;" class="wu-max-w-screen-md wu-mx-auto wu-my-10 wu-p-12 wu-bg-white wu-shadow wu-text-justify">

    <p class="wu-text-lg wu-leading-relaxed">
      The next step on our journey!
    </p>

    <h1 class="wu-text-3xl">
    	WP Ultimo 2.1 is here.<br>Say hello to <span class="wu-font-bold">Nara</span>!
    </h1>

    <p class="wu-text-lg wu-leading-relaxed">
      Hello everyone,
		</p>

		<p class="wu-text-lg wu-leading-relaxed">
			Here I am again - after a ton of work, bug-fixing, and tests - to report that WP Ultimo version 2.1 is here!
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			Version 2.0 was a completely rewrite of WP Ultimo, and the following versions helped us realize which aspects of it would need to be improved to take our software to the next level once more.
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			This version focuses on stability, fixing the vast majority of bugs present in 2.0.X releases and making sure PHP 8+ is completely supported - while keeping 7.4.30 supported as well. It will serve as a solid base that can support the transition to more modern tooling, allowing us to overcome some limitations of the WordPress ecosystem and really embrace what modern PHP has to offer.
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			As a Brazilian company, we've been trying to honor Brazilian music by naming major and minor releases after incredibly talented Brazilian musicians.
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			WP Ultimo 2.1 is named Nara, after one of the most important voices of Brazilian Bossa Nova and Samba: <a href="https://en.wikipedia.org/wiki/Nara_Le%C3%A3o" target="_blank">Nara Leão</a>.
    </p>

		<div class="wu-inline-block wu-float-right wu-ml-8 wu-mb-4">
      <img class="wu-block wu-rounded" src="<?php echo wu_get_asset('nara-leao.png'); ?>" width="200">
      <small class="wu-block wu-mt-1">Nara Leão</small>
    </div>

		<p class="wu-text-lg wu-leading-relaxed">
			If you want to enjoy a calm and peaceful moment imagining a sunset on one of Rio de Janeiro's beaches, just listen to <a href="https://www.youtube.com/watch?v=1v-NeQrmm3E" target="_blank">O Barquinho</a>. However, if you want to discover Nara's more combative side, try <a href="https://www.youtube.com/watch?v=a-6MBY-7kp8" target="_blank">Opinião</a>, a song that became an anthem of resistance in Brazil.
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			If you like what you hear and want to hear more, check out <a href="https://open.spotify.com/playlist/37i9dQZF1DZ06evO3AdM6Z?si=8ea3283cbcfa41d0" target="_blank">this playlist on Spotify</a>.
    </p>

		<p class="wu-text-lg wu-leading-relaxed">
			As always, let me know if you need anything!
    </p>

		<p class="wu-text-lg wu-leading-relaxed wu-mb-8">
			Yours truly,
    </p>

    <p class="wu-text-lg wu-leading-relaxed wu-mb-0">

      <?php echo get_avatar('arindo@wpultimo.com', 64, '', 'Arindo Duque', [
          'class' => 'wu-rounded-full',
      ]); ?>

      <strong class="wu-block">Arindo Duque</strong>
      <small class="wu-block">Founder and CEO of NextPress, the makers of WP Ultimo</small>
    </p>

  </div>

  <div style="max-width: 700px;" class="wu-max-w-screen-md wu-mx-auto wu-mb-10">

    <hr class="hr-text wu-my-4 wu-text-gray-800" data-content="THIS VERSION WAS CRAFTED WITH LOVE BY">

    <?php

    $key_people = [
        'arindo' => [
            'email' => 'arindo@wpultimo.com',
            'signature' => 'arindo.png',
            'name' => 'Arindo Duque',
            'position' => 'Founder and CEO',
        ],
        'advaldo' => [
            'email' => 'advaldo@wpultimo.com',
            'signature' => '',
            'name' => 'Advaldo Medeiros',
            'position' => 'Project Manager',
        ],
        'allyson' => [
            'email' => 'allyson@wpultimo.com',
            'signature' => '',
            'name' => 'Allyson Souza',
            'position' => 'Developer',
        ],
        'anyssa' => [
            'email' => 'anyssa@wpultimo.com',
            'signature' => '',
            'name' => 'Anyssa Ferreira',
            'position' => 'Designer',
        ],
        'gustavo' => [
            'email' => 'gustavo@wpultimo.com',
            'signature' => '',
            'name' => 'Gustavo Modesto',
            'position' => 'Developer',
        ],
        'juliana' => [
            'email' => 'juliana@wpultimo.com',
            'signature' => '',
            'name' => 'Juliana Dias Gomes',
            'position' => 'Do-it-all',
        ],
        'lucas-carvalho' => [
            'email' => 'lucas@wpultimo.com',
            'signature' => '',
            'name' => 'Lucas Carvalho',
            'position' => 'Developer',
        ],
        'lucas-lauer' => [
            'email' => 'lauer@wpultimo.com',
            'signature' => '',
            'name' => 'Lucas Lauer',
            'position' => 'Support',
        ],
        'rodinei' => [
            'email' => 'rodinei@wpultimo.com',
            'signature' => '',
            'name' => 'Rodinei Costa',
            'position' => 'Developer',
        ],
        'ruel' => [
            'email' => 'ruel@wpultimo.com',
            'signature' => '',
            'name' => 'Ruel Porlas',
            'position' => 'Support',
        ],
        'yan' => [
            'email' => 'yan@wpultimo.com',
            'signature' => '',
            'name' => 'Yan Kairalla',
            'position' => 'Developer',
        ],
    ];

?>

    <div class="wu-flex wu-flex-wrap wu-mt-8">

      <?php foreach ($key_people as $person) { ?>

        <div class="wu-text-center wu-w-1/4 wu-mb-5">

          <?php
      echo get_avatar($person['email'], 64, '', 'Arindo Duque', [
          'class' => 'wu-rounded-full',
      ]);
          ?>
          <strong class="wu-text-base wu-block"><?php echo $person['name']; ?></strong>
          <small class="wu-text-xs wu-block"><?php echo $person['position']; ?></small>

        </div>

      <?php } ?>

    </div>

  </div>

</div>

<style>
.hr-text {
  line-height: 1em;
  position: relative;
  outline: 0;
  border: 0;
  /* color: black; */
  text-align: center;
  height: 1.5em;
  opacity: .5;
}
.hr-text:before {
  content: '';
  background: -webkit-gradient(linear, left top, right top, from(transparent), color-stop(#818078), to(transparent));
  background: linear-gradient(to right, transparent, #818078, transparent);
  position: absolute;
  left: 0;
  top: 50%;
  width: 100%;
  height: 1px;
}
.hr-text:after {
  content: attr(data-content);
  position: relative;
  display: inline-block;
  /* color: black; */
  padding: 0 .5em;
  line-height: 1.5em;
  color: #818078;
  background-color: #eef2f5;
}
</style>
