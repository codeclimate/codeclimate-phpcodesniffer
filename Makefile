.PHONY: image

IMAGE_NAME ?= codeclimate/codeclimate-phpcodesniffer

image:
	docker build --rm -t $(IMAGE_NAME) .
