.PHONY: image

IMAGE_NAME ?= codeclimate/codeclimate-phpcodesniffer

composer-update:
	docker run \
	  --rm \
	  --volume $(PWD)/composer.json:/usr/src/app/composer.json:ro \
	  --volume $(PWD)/composer.lock:/usr/src/app/composer.lock \
	  $(IMAGE_NAME) \
	  sh -c 'cd /usr/src/app && composer update'

image:
	docker build --rm -t $(IMAGE_NAME) .
