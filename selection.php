<?php include 'partials/header.php'; ?>
<section class="description">
    <div class="container description_container">
        <div class="description_block">
            <h3>Check this instructions before using this system.</h3>
                <br>
                <h4><ol>
                        <li style="font-weight:600;">1. Log in to your account.</li>
                        <li style="font-weight:600;">2. Choose one of the two methods below to search for specialists. </li>
                        <br>
                        <li style="font-weight:600;">3. If you want to make a team of medical specialists, click on the first button.</li>
                        <li>3.1. To add a new specialization to form a set of specialists, you need to click 
                            the button "Add new" and select the desired specialization from the proposed list.</li>
                        <li>3.2. If you enter a specialization incorrectly, you can change it by clicking on 
                            the same specialization button.</li>
                        <li>3.3. To clear all specializations, you only need to press a button "Clear all".</li>
                        <li>3.4. to start the algorithm of automatic selection of specialists according to 
                            the entered specializations, you need to click the button "Create set".</li>
                        <br>
                        <li style="font-weight:600;">4. If you want to search for one specialization, click on the second button.</li>
                        <li>4.1. To search for all specialists by one specialization, it is worth choosing 
                            it by the corresponding button of the name of the specialization.</li>
                        <br>
                        <li>5. After finding specialists, you can view their detailed information by 
                        clicking on the button "More info" and for quick communication with candidates there is a button "Contact".</li>
                    </ol>
                </h4>
            <div class="buttons_block">
                <a href="<?= ROOT_URL ?>selection.php">Make a team of medical specialists</a>
                <a href="<?= ROOT_URL ?>specialization.php" id="secondary_button">Search for one specialization</a>
            </div>
        </div>
    </div>
</section>
<?php if ($authorized): ?>
    <section class="selection">
        <div class="container selection_container">
            <h3>Choose a set of medical specialists</h3>
            <br>
            <!-- selection tools -->
            <div class="selection__buttons">
                <button id="add">
                    <div class="plus"></div> Add new
                </button>
                <button id="clear__all">
                    <div class="plus" style="transform:rotate(45deg)"></div> Clear all
                </button>
            </div>
            <div id="select__specializations" class="select__specializations">
                <!-- Динамічно додані select__specialization елементи будуть тут -->
            </div>
            <button id="create__set">
                <div class="check"></div>
                <p style="font-weight: bold;">Create set</p>
            </button>
            <br>
            <div class="specialists__container"></div>
        </div>
    </section>
<?php else: ?>
    <section class="permission">
        <div class="container description_container">
            <div class="description_block">
                <div id="no__permission">
                    <h1>!</h1>
                    <h3>NO permission to use the system!</h3>
                </div>
                <br>
                <h4>HINT: Log In to fix it.</h4>
            </div>
        </div>
    </section>
<?php endif; ?>
<script src="js/selection.js"></script> 
</body>
</html>