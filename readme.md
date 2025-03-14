# 🚀 Executando as Migrações
cd application
Para rodar as migrações pendentes:
vendor/bin/phinx migrate 

### Para reverter a última migração:
```bash
vendor/bin/phinx rollback -t 0
```

### Para ver o status das migrações:

```bash
vendor/bin/phinx status
```

# rodar
# Para o banco tracker
vendor/bin/phinx migrate -e tracker_development

# Para o banco users
vendor/bin/phinx migrate -e users_development -p users_migrations



# Criar migração para tracker
vendor/bin/phinx create CreateTrackerTable

# Criar migração para users
vendor/bin/phinx create CreateUsersTable

Senha do sistema:
ba6548tbsi

docker exec -it php /bin/bash