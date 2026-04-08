.PHONY: install setup up down migrate seed logs reset artisan demo

install:
	docker compose run --rm app composer install
	cd frontend && npm install

setup: install migrate seed

up:
	docker compose up -d

down:
	docker compose down

migrate:
	docker compose exec app php artisan migrate --database=write --path=database/migrations/write --force
	docker compose exec app php artisan migrate --database=read --path=database/migrations/read --force

seed:
	docker compose exec app php artisan db:seed --force

logs:
	docker compose logs -f

reset:
	docker compose down
	rm -f backend/database/write.sqlite backend/database/read.sqlite
	touch backend/database/write.sqlite backend/database/read.sqlite
	docker compose up -d
	sleep 3
	$(MAKE) migrate seed

artisan:
	docker compose exec app php artisan $(cmd)

demo:
	docker compose exec app php artisan db:seed --class=DemoSeeder --force
