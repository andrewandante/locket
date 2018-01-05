<% if $IsMobile %>
<div class="navmenu navmenu-default navmenu-fixed-top">
<% else %>
<div class="navmenu navmenu-default navmenu-fixed-left">
<% end_if %>
    <ul class="nav navmenu-nav">
        <li><a href=$BaseURL>Home</a></li>
        <li><a href="{$BaseURL}gallery" >Gallery</a></li>
        <li><a href="https://atawalkingspeed.com">Blog  <i class="fa fa-external-link" aria-hidden="true"></i></a></li>
    </ul>
    <div class="navmenu-brand">
        <h1>$SiteConfig.Title</h1>
        <h4>$SiteConfig.Tagline</h4>
    </div>
    <div class="copyright-text">©Copyright <a href="https://themewagon.com/"> ThemeWagon</a> 2015 </div>
</div>