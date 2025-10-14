#!/bin/bash

echo "🚀 Iniciando setup do Docker para Laravel API..."

# Cores para output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Verificar se o Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker e tente novamente."
    exit 1
fi

# Função para rodar docker compose (suporta V1 e V2)
DC="docker compose"
if ! $DC version >/dev/null 2>&1; then
  DC="docker-compose"
fi

echo "${YELLOW}📦 Construindo containers...${NC}"
$DC build

echo "${YELLOW}🚀 Iniciando containers...${NC}"
$DC up -d


# Criar .env se não existir
echo "${YELLOW}🔑 Copiando arquivo .env...${NC}"
if [ ! -f .env ]; then
    cp .env.example.docker .env
    echo "${GREEN}✅ Arquivo .env criado${NC}"
else
    echo "${YELLOW}⚠️  Arquivo .env já existe${NC}"
fi

# Gerar APP_KEY
echo "${YELLOW}🔐 Gerando APP_KEY...${NC}"
$DC exec -T app php artisan key:generate || echo "${RED}❌ Falha ao gerar APP_KEY${NC}"

# Executar migrations
echo "${YELLOW}🗄️  Executando migrations...${NC}"
$DC exec -T app php artisan migrate --force || echo "${RED}❌ Erro ao executar migrations${NC}"

# Limpar cache
echo "${YELLOW}🔄 Limpando cache...${NC}"
$DC exec -T app php artisan config:clear
$DC exec -T app php artisan cache:clear
$DC exec -T app php artisan route:clear
$DC exec -T app php artisan view:clear

# Permissões
echo "${YELLOW}📝 Configurando permissões...${NC}"
$DC exec -T app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
$DC exec -T app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo ""
echo "${GREEN}✅ Setup concluído com sucesso!${NC}"
echo ""
echo "📋 Informações importantes:"
echo "   🌐 API URL: http://localhost:8000"
echo "   🗄️  MySQL Host: localhost:3307"
echo "   🧩 MySQL Database: laravel_db"
echo "   👤 MySQL User: laravel_user"
echo "   🔑 MySQL Password: laravel_password"
echo ""
echo "🛠️  Comandos úteis:"
echo "   📜 Ver logs: $DC logs -f"
echo "   ⏹️  Parar containers: $DC down"
echo "   🔁 Reiniciar: $DC restart"
echo "   🧠 Entrar no container: $DC exec app bash"
echo "   🗃️  Executar migrations: $DC exec app php artisan migrate"
echo ""
