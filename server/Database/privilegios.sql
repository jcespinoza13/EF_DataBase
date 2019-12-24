GRANT USAGE ON *.* TO `nextu`@`localhost`;

GRANT SELECT, INSERT, UPDATE, DELETE ON `ef\_nextu\_db`.* TO `nextu`@`localhost`;

GRANT SELECT, INSERT, UPDATE, DELETE ON `ef_nextu_db`.`eventos` TO `nextu`@`localhost`;

GRANT SELECT (`password`, `usuario`), INSERT, UPDATE (`fechaNac`, `nombre`, `password`, `usuario`) ON `ef_nextu_db`.`usuarios` TO `nextu`@`localhost`;
