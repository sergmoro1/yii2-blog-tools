<ul class="list-inline sociallikes"><small><?= $call ?></small>
    <?php foreach($socialLikes as $name => $social): ?>
    <li>
        <a onclick="popUp=window.open('<?= $social['resource'] ?>','sharer','scrollbars=yes,width=800,height=400');popUp.focus();return false;" href="javascript:;"
            title="<?= $tagTitle ?> <?= $name ?>">
            <span class="fa-stack fa-sm">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="<?= $social['icon'] ?> fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
