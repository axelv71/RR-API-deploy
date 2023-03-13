#!/bin/bash

# Démarrer le conteneur en arrière-plan
docker compose up -d

# Attendre 5 secondes pour permettre au conteneur de démarrer complètement
sleep 3

# Afficher les journaux en temps réel
docker logs -f www_cube



