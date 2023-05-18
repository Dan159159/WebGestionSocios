FROM httpd:2.4.51

# Copiar archivo de configuración personalizado
COPY gestionDan.apache.conf /usr/local/apache2/conf/gestionDan.apache.conf

# Agregar línea para incluir el archivo de configuración personalizado
RUN echo "Include /usr/local/apache2/conf/gestionDan.apache.conf" \
    >> /usr/local/apache2/conf/httpd.conf

# Cambiar comando CMD para usar httpd en lugar de httpd-foreground
CMD ["httpd", "-D", "FOREGROUND"]
