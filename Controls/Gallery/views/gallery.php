<div class="lolita-collection-wrapper">
    <?php echo $l10n; ?>

    <script id="lolita-collection-item-template" type="text/template">
        <li>
            <input type="hidden" name="<?php echo $name;?>[]" value="<%= value %>"/>
            <div class="lolita-collection__item">
                <div class="centered">
                    <img src="<%= image %>" alt="Collection Item">
                </div>
                <a class="check" title="Remove" href="#">
                    <div class="media-modal-icon"></div>
                </a>
            </div>
        </li>
    </script>

    <div class="lolita-collection-container">
        <!-- Collection -->
        <div class="lolita-collection">
            <ul class="lolita-collection-list ui-sortable">
            </ul>
        </div>
        <!-- End collection -->
    </div>

    <div class="lolita-collection-buttons">
        <button id="lolita-collection-add" type="button" class="button button-primary">Add</button>
        <button id="lolita-collection-remove" type="button" class="button lolita-button-remove hide">Remove</button>
    </div>
</div>