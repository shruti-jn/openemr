# Use official OpenEMR production image (matches docker/production/docker-compose.yml)
FROM openemr/openemr:7.0.4

# Copy the AgentForge custom module files into the container
COPY --chown=apache:apache interface/modules/custom_modules/ /var/www/localhost/htdocs/openemr/interface/modules/custom_modules/

# Copy example SQL data
COPY --chown=apache:apache sql/ /var/www/localhost/htdocs/openemr/sql_custom/

# Expose HTTP and HTTPS ports
EXPOSE 80 443
