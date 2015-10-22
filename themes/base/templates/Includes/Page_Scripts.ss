<% if $WebpackDevServer %><%-- Site javascript, compiled by webpack --%>
    <script src="http://localhost:3000/production/js/main.js"></script>
<% else %>
    <script src="$HashPath('production/js/main.js')"></script>
<% end_if %>
