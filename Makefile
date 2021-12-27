.PYTHON: ALL

upload:
	git pull
	git add -A
	git commit -m "add something"
	git push