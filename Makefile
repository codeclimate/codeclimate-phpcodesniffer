.PHONY: image release

IMAGE_NAME ?= codeclimate/codeclimate-phpcodesniffer
RELEASE_REGISTRY ?= codeclimate

ifndef RELEASE_TAG
override RELEASE_TAG = latest
endif

composer-update:
	docker run \
	  --rm \
	  --volume $(PWD)/composer.json:/usr/src/app/composer.json:ro \
	  --volume $(PWD)/composer.lock:/usr/src/app/composer.lock \
	  $(IMAGE_NAME) \
	  sh -c 'cd /usr/src/app && composer update'

image:
	docker build --rm -t $(IMAGE_NAME) .

release:
	docker tag $(IMAGE_NAME) $(RELEASE_REGISTRY)/codeclimate-phpcodesniffer:$(RELEASE_TAG)
	docker push $(RELEASE_REGISTRY)/codeclimate-phpcodesniffer:$(RELEASE_TAG)
