-- ================================================
USE [SIGEUNBB]
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [bib].[Sp_DEL_tipo_prestamos]
	@_iTipoPrestamoId INTEGER

AS
BEGIN
	SET NOCOUNT ON;

	DELETE FROM bib.tipo_prestamos WHERE iTipoPrestamoId=@_iTipoPrestamoId
	
	IF @@ROWCOUNT>0
		BEGIN
			SELECT 1 iResult
			RETURN 1
		END
ErrorCapturado:
	SELECT 0 iResult
	RETURN 0
    
END
GO
-- ================================================
