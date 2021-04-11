<?php

/**
 * Template for the actual search form that can be used 
 * throughout site with get_search_form(), similar to 
 * get_header() and get_footer()
 * 
 * The built-in search is at example.com/?s={searchTerm}. 
 * The action makes sure the form is submitted to root. 
 * The "name" attribute "s" on the search input will become 
 * a query parameter on a GET request.
 */
?>
<form class="search-form" method="get" action="<?php echo site_url("/") ?>">
  <label class="headline headline--medium" for="s">New Search</label>
  <div class="search-form-row">
    <input class="s" id="s" type="search" name="s" placeholder="What are you looking for?">
    <input class="search-submit" type="submit" value="Search">
  </div>
</form>