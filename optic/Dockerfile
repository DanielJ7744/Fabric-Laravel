FROM node
EXPOSE 80
EXPOSE 34444
WORKDIR /data

RUN adduser --disabled-login optic && chown -R optic /data
RUN npm install --global @useoptic/cli

USER optic
ENTRYPOINT ["api"]
