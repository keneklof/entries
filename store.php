<?php
echo $competition[0]["competition_name"] . "<br>";
echo $competition[0]["competition_location"] . "<br>";
echo $competition[0]["competition_start"] . "<br>";
echo $competition[0]["competition_end"] . "<br>";
echo $competition[0]["competition_soaringspot"] . "<br>";
echo "<a href=" . $competition[0]['competition_soaringspot'] . " target='_blank'>SoaringSpot</a>" . "<br>";
//ACCORDION
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingThree">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
            Accordion Item #3
          </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
          </div>
        </div>
      </div>
