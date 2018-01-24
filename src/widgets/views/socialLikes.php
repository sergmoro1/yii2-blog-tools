<?php
use sergmoro1\blog\Module;
?>

<ul class="list-inline text-right">
    <li>
        <a onclick="popUp=window.open('http://vk.com/share.php?url=<?= $url ?>&amp;title=<?= $title ?><?= $image ? "&amp;image=$image" : "" ?>','sharer','scrollbars=yes,width=800,height=400');popUp.focus();return false;" href="javascript:;"
            title="<?= Module::t('core', 'Share link on ') ?> Vk">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-vk fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <li>
        <a onclick="popUp=window.open('http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl=<?= $url ?>','sharer','scrollbars=yes,width=800,height=400');popUp.focus();return false;" href="javascript:;"
            title="<?= Module::t('core', 'Share link on ') ?> Odnoklassniki">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-odnoklassniki fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <li>
        <a href="javascript:;" onclick="popUp=window.open('https://plus.google.com/share?url=<?= $url ?>','sharer','scrollbars=yes,width=800,height=400');popUp.focus();return false;"
            title="<?= Module::t('core', 'Share link on ') ?> Google+">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-google-plus fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
</ul>
