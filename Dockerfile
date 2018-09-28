FROM pagarme/magento

COPY ./magentosmtp /opt/docker/magentosmtp
COPY ./start2 /opt/docker/start2

ENTRYPOINT ["/usr/bin/env"]
CMD ["bash", "/opt/docker/start2"]
