<?php

?>


<h3> design fro Mobile web </h3>
<br/> <br/> <h3 class="well well-sm"> Responsive Design  </h3>

<div >

<div class="visible-lg">
Available classes</h2>
<p>Use a single or combination of the available classes for toggling content across viewport breakpoints.</p>
<div class="table-responsive">
    <table class="table table-bordered table-striped responsive-utilities">
        <thead>
        <tr>
            <th></th>
            <th>
                Extra small devices
                <small>Phones (&lt;768px)</small>
            </th>
            <th>
                Small devices
                <small>Tablets (≥768px)</small>
            </th>
            <th>
                Medium devices
                <small>Desktops (≥992px)</small>
            </th>
            <th>
                Large devices
                <small>Desktops (≥1200px)</small>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th><code>.visible-xs</code></th>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
        </tr>
        <tr>
            <th><code>.visible-sm</code></th>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
        </tr>
        <tr>
            <th><code>.visible-md</code></th>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
        </tr>
        <tr>
            <th><code>.visible-lg</code></th>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
        </tr>
        </tbody>
        <tbody>
        <tr>
            <th><code>.hidden-xs</code></th>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
        </tr>
        <tr>
            <th><code>.hidden-sm</code></th>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
        </tr>
        <tr>
            <th><code>.hidden-md</code></th>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
        </tr>
        <tr>
            <th><code>.hidden-lg</code></th>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
        </tr>
        </tbody>
    </table>
</div>


<h2 id="responsive-utilities-print">Print classes</h2>
<p>Similar to the regular responsive classes, use these for toggling content for print.</p>
<div class="table-responsive">
    <table class="table table-bordered table-striped responsive-utilities">
        <thead>
        <tr>
            <th>Class</th>
            <th>Browser</th>
            <th>Print</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th><code>.visible-print</code></th>
            <td class="is-hidden">Hidden</td>
            <td class="is-visible">Visible</td>
        </tr>
        <tr>
            <th><code>.hidden-print</code></th>
            <td class="is-visible">Visible</td>
            <td class="is-hidden">Hidden</td>
        </tr>
        </tbody>
    </table>
</div>


<h2 id="responsive-utilities-tests">Test cases</h2>
<p>Resize your browser or load on different devices to test the responsive utility classes.</p>

<h3>Visible on...</h3>
<p>Green checkmarks indicate the element <strong>is visible</strong> in your current viewport.</p>
<div class="row responsive-utilities-test visible-on">
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-xs">Extra small</span>
        <span class="visible-xs">✔ Visible on x-small</span>
    </div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-sm">Small</span>
        <span class="visible-sm">✔ Visible on small</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-md">Medium</span>
        <span class="visible-md">✔ Visible on medium</span>
    </div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-lg">Large</span>
        <span class="visible-lg">✔ Visible on large</span>
    </div>
</div>
<div class="row responsive-utilities-test visible-on">
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-sm">Extra small and small</span>
        <span class="visible-xs visible-sm">✔ Visible on x-small and small</span>
    </div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-md hidden-lg">Medium and large</span>
        <span class="visible-md visible-lg">✔ Visible on medium and large</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-md">Extra small and medium</span>
        <span class="visible-xs visible-md">✔ Visible on x-small and medium</span>
    </div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-sm hidden-lg">Small and large</span>
        <span class="visible-sm visible-lg">✔ Visible on small and large</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-lg">Extra small and large</span>
        <span class="visible-xs visible-lg">✔ Visible on x-small and large</span>
    </div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-sm hidden-md">Small and medium</span>
        <span class="visible-sm visible-md">✔ Visible on small and medium</span>
    </div>
</div>

<h3>Hidden on...</h3>
<p>Here, green checkmarks also indicate the element <strong>is hidden</strong> in your current viewport.</p>
<div class="row responsive-utilities-test hidden-on">
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-xs">Extra small</span>
        <span class="visible-xs">✔ Hidden on x-small</span>
    </div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-sm">Small</span>
        <span class="visible-sm">✔ Hidden on small</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-md">Medium</span>
        <span class="visible-md">✔ Hidden on medium</span>
    </div>
    <div class="col-xs-6 col-sm-3">
        <span class="hidden-lg">Large</span>
        <span class="visible-lg">✔ Hidden on large</span>
    </div>
</div>
<div class="row responsive-utilities-test hidden-on">
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-sm">Extra small and small</span>
        <span class="visible-xs visible-sm">✔ Hidden on x-small and small</span>
    </div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-md hidden-lg">Medium and large</span>
        <span class="visible-md visible-lg">✔ Hidden on medium and large</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-md">Extra small and medium</span>
        <span class="visible-xs visible-md">✔ Hidden on x-small and medium</span>
    </div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-sm hidden-lg">Small and large</span>
        <span class="visible-sm visible-lg">✔ Hidden on small and large</span>
    </div>
    <div class="clearfix visible-xs"></div>
    <div class="col-xs-6 col-sm-6">
        <span class="hidden-xs hidden-lg">Extra small and large</span>
        <span class="visible-xs visible-lg">✔ Hidden on x-small and large</span>
    </div>
    <div class="col-xs-6 col-sm-6">
      <span class="hidden-sm hidden-md">Small and medium

</div>
</div>
</div>

<div class=" visible-xs visible-sm visible-md visible-lg responsive-utilities">
    <p> This is a test for all  </p>
    <article>
        See this ?


    </article>
    <section> Adn this one ?</section>
</div>



<div class=" hidden-xs hidden-sm">
    <br/> <br/> <br/>
    <span class="label label-info">Not phone, tablet</span>

    <p> This must NOT seend in  phones adn tables  </p>
    <article>
        See this ?


    </article>
    <section> Adn this one ?</section>
</div>


<div class=" visible-xs visible-sm">
    <br/> <br/> <br/>
    <span class="label label-danger"> Only from phone, tablet</span>

    <p> This must   see  ONLY in  phones adn tables  </p>
    <article>
        See this ?


    </article>
    <section> Adn this one ?</section>
</div>

<div class=" visible-sm ">
    <br/> <br/> <br/>
    <span class="label label-danger"> Only from tablets </span>

    <p> This must   see  ONLY in    adn tables  </p>
    <article>
        See this ?


    </article>
    <section> Adn this one ?</section>
</div>


<div class=" visible-xs ">
    <br/> <br/> <br/>
    <span class="label label-danger"> Only from phone </span>

    <p> This must   see  ONLY in  phones adn tables  </p>
    <article>
        See this ?


    </article>
    <section> Adn this one ?</section>
</div>